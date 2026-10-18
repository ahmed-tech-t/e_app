<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\CloudflareImageService;
use App\Application\Services\ImageCompressionService;
use App\Infrastructure\Persistence\Models\UploadedImage;
use App\Interfaces\Http\Requests\Image\UploadImageRequest;
use App\Interfaces\Http\Resources\UploadedImageResource;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImageController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly ImageCompressionService $compressionService,
        private readonly CloudflareImageService $cloudflareService,
    ) {}

    /**
     * List uploaded images.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $images = UploadedImage::query()->latest()->paginate($perPage);

        return $this->success(
            UploadedImageResource::collection($images->items()),
            'Images retrieved successfully',
            [
                'total' => $images->total(),
                'per_page' => $images->perPage(),
                'current_page' => $images->currentPage(),
                'last_page' => $images->lastPage(),
            ]
        );
    }

    /**
     * Upload an image:
     * 1. Validate file.
     * 2. Check size; if > 3MB, compress under 3MB.
     * 3. Upload to Cloudflare.
     * 4. Save link in database.
     */
    public function store(UploadImageRequest $request): JsonResponse
    {
        $file = $request->file('image');
        $compressed = null;

        try {
            // Compress under 3MB if needed
            $compressed = $this->compressionService->compressUnderLimit($file);

            // Upload to Cloudflare
            $uploadResult = $this->cloudflareService->upload($compressed);

            // Save record in database
            $imageRecord = DB::transaction(function () use ($uploadResult, $compressed) {
                return UploadedImage::create([
                    'url' => $uploadResult->url,
                    'cloudflare_id' => $uploadResult->id,
                    'filename' => $uploadResult->filename ?? $compressed->filename,
                    'size_bytes' => $compressed->sizeBytes,
                    'mime_type' => $compressed->mimeType,
                ]);
            });

            return $this->success(
                UploadedImageResource::make($imageRecord),
                'Image uploaded successfully',
                code: 201
            );
        } catch (Throwable $e) {
            return $this->error(
                data: ['error' => $e->getMessage()],
                message: 'Failed to process and upload image: '.$e->getMessage(),
                code: 500
            );
        } finally {
            if ($compressed && $compressed->isTemporary && file_exists($compressed->filePath)) {
                @unlink($compressed->filePath);
            }
        }
    }

    /**
     * Show single uploaded image.
     */
    public function show(int $id): JsonResponse
    {
        $image = UploadedImage::find($id);
        if (! $image) {
            return $this->error(null, 'Image not found', 404);
        }

        return $this->success(UploadedImageResource::make($image));
    }

    /**
     * Update / Replace an existing image:
     * 1. Compress & upload new image to Cloudflare.
     * 2. Delete old image from Cloudflare.
     * 3. Update database record with new URL and Cloudflare ID.
     */
    public function update(UploadImageRequest $request, int $id): JsonResponse
    {
        $image = UploadedImage::find($id);
        if (! $image) {
            return $this->error(null, 'Image not found', 404);
        }

        $oldCloudflareId = $image->cloudflare_id ?: $image->url;
        $file = $request->file('image');
        $compressed = null;

        try {
            $compressed = $this->compressionService->compressUnderLimit($file);
            $uploadResult = $this->cloudflareService->upload($compressed);

            DB::transaction(function () use ($image, $uploadResult, $compressed) {
                $image->update([
                    'url' => $uploadResult->url,
                    'cloudflare_id' => $uploadResult->id,
                    'filename' => $uploadResult->filename ?? $compressed->filename,
                    'size_bytes' => $compressed->sizeBytes,
                    'mime_type' => $compressed->mimeType,
                ]);
            });

            // Delete previous image from Cloudflare
            if (! empty($oldCloudflareId)) {
                $this->cloudflareService->delete($oldCloudflareId);
            }

            return $this->success(
                UploadedImageResource::make($image->fresh()),
                'Image updated successfully'
            );
        } catch (Throwable $e) {
            return $this->error(
                data: ['error' => $e->getMessage()],
                message: 'Failed to update image: '.$e->getMessage(),
                code: 500
            );
        } finally {
            if ($compressed && $compressed->isTemporary && file_exists($compressed->filePath)) {
                @unlink($compressed->filePath);
            }
        }
    }

    /**
     * Delete image:
     * 1. Delete image from Cloudflare.
     * 2. Remove record from database.
     */
    public function destroy(int $id): JsonResponse
    {
        $image = UploadedImage::find($id);
        if (! $image) {
            return $this->error(null, 'Image not found', 404);
        }

        $cloudflareId = $image->cloudflare_id ?: $image->url;

        // Delete from Cloudflare
        if (! empty($cloudflareId)) {
            $this->cloudflareService->delete($cloudflareId);
        }

        $image->delete();

        return $this->success(
            ['deleted' => true, 'id' => $id],
            'Image deleted successfully'
        );
    }
}
