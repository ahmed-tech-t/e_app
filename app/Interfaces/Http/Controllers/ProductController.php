<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\ProductService;
use App\Application\Services\ImageCompressionService;
use App\Application\Services\CloudflareImageService;
use App\Domain\PaginatorMeta;
use App\Infrastructure\Persistence\utils\Constants;
use App\Interfaces\Http\Requests\Product\CreateProductRequest;
use App\Interfaces\Http\Requests\Product\ProductSearchRequest;
use App\Interfaces\Http\Requests\Product\UpdateProductRequest;
use App\Interfaces\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends BaseController
{
    protected string $resourceClass = ProductResource::class;
    protected string $searchRequest = ProductSearchRequest::class;
    protected string $storeRequest = CreateProductRequest::class;
    protected string $updateRequest = UpdateProductRequest::class;

    public function __construct(
        private ProductService $productService,
        private readonly ImageCompressionService $compressionService,
        private readonly CloudflareImageService $cloudflareService,
    ) {
        $this->service = $productService;
    }

    // Store product with optional image upload to Cloudflare
    public function store(): JsonResponse
    {
        $request = app($this->storeRequest);
        $file = $request->hasFile('image') ? $request->file('image') : null;
        $imageUrl = null;
        $compressed = null;
        if ($file) {
            $compressed = $this->compressionService->compressUnderLimit($file);
            $uploadResult = $this->cloudflareService->upload($compressed);
            $imageUrl = $uploadResult->url;
        }
        $dto = $request->toDto();
        $dto->image = $imageUrl;
        $entity = $this->productService->create($dto);
        if ($compressed && $compressed->isTemporary && file_exists($compressed->filePath)) {
            @unlink($compressed->filePath);
        }
        return $this->success(ProductResource::make($entity));
    }

    // Update product, replace image if new one provided, delete old from Cloudflare
    public function update(int $id): JsonResponse
    {
        $request = app($this->updateRequest);
        $product = $this->productService->findById($id);
        $oldImage = $product->image ?? null;
        $file = $request->hasFile('image') ? $request->file('image') : null;
        $imageUrl = $oldImage;
        $compressed = null;
        if ($file) {
            $compressed = $this->compressionService->compressUnderLimit($file);
            $uploadResult = $this->cloudflareService->upload($compressed);
            $imageUrl = $uploadResult->url;
        }
        $dto = $request->toDto();
        $dto->image = $imageUrl;
        $entity = $this->productService->update($dto, $id);
        if ($file && $oldImage) {
            $this->cloudflareService->delete($oldImage);
        }
        if ($compressed && $compressed->isTemporary && file_exists($compressed->filePath)) {
            @unlink($compressed->filePath);
        }
        return $this->success(ProductResource::make($entity));
    }

    // Delete product and its Cloudflare image
    public function destroy(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);
        $image = $product->image ?? null;
        if ($image) {
            $this->cloudflareService->delete($image);
        }
        $message = $this->productService->destroy($id);
        return $this->success($message);
    }

    // List products (existing behaviour)
    public function index(Request $request)
    {
        $items = $this->service->findAll($request->all());
        return $this->success(data: ($this->resourceClass)::collection($items));
    }

    public function findAllByLocation(Request $request, int $locationId)
    {
        $perPage = $request->input('per_page', Constants::DEFAULT_PER_PAGE);
        $items = $this->service->findAllByLocation($locationId, $perPage);
        $meta = new PaginatorMeta($items);
        return $this->success(data: ($this->resourceClass)::collection($items), meta: $meta->toArray());
    }

    public function findByLocation(int $productId, int $locationId)
    {
        $product = $this->service->findByLocation($productId, $locationId);
        return $this->success(data: ($this->resourceClass)::make($product));
    }
}
