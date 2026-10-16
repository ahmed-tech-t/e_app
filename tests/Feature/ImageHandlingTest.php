<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Application\DTOs\CloudflareImageUploadResult;
use App\Application\Services\CloudflareImageService;
use App\Application\Services\ImageCompressionService;
use App\Infrastructure\Persistence\Models\UploadedImage;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImageHandlingTest extends TestCase
{
    use RefreshDatabase;

    private string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = Constants::BASE_API_URL . '/v1';
    }

    public function test_uploads_small_image_under_3mb_to_cloudflare_and_saves_to_database(): void
    {
        $mockService = $this->mock(CloudflareImageService::class);
        $mockService->shouldReceive('upload')
            ->once()
            ->andReturn(new CloudflareImageUploadResult(
                id: 'cf-test-image-uuid-1',
                url: 'https://imagedelivery.net/account-hash/cf-test-image-uuid-1/public',
                variants: [
                    'https://imagedelivery.net/account-hash/cf-test-image-uuid-1/public',
                    'https://imagedelivery.net/account-hash/cf-test-image-uuid-1/thumbnail',
                ],
                filename: 'avatar.jpg',
            ));

        $file = UploadedFile::fake()->image('avatar.jpg', 600, 600);

        $response = $this->postJson("{$this->baseUrl}/images", [
            'image' => $file,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'cloudflare_id' => 'cf-test-image-uuid-1',
                'url' => 'https://imagedelivery.net/account-hash/cf-test-image-uuid-1/public',
                'filename' => 'avatar.jpg',
            ],
        ]);

        $this->assertDatabaseHas('uploaded_images', [
            'cloudflare_id' => 'cf-test-image-uuid-1',
            'url' => 'https://imagedelivery.net/account-hash/cf-test-image-uuid-1/public',
            'filename' => 'avatar.jpg',
        ]);
    }

    public function test_compresses_large_image_over_3mb_under_limit_before_upload(): void
    {
        $mockService = $this->mock(CloudflareImageService::class);
        $mockService->shouldReceive('upload')
            ->once()
            ->andReturn(new CloudflareImageUploadResult(
                id: 'cf-large-compressed-id',
                url: 'https://imagedelivery.net/account-hash/cf-large-compressed-id/public',
                variants: [
                    'https://imagedelivery.net/account-hash/cf-large-compressed-id/public',
                ],
                filename: 'large.jpg',
            ));

        // Create a 5MB fake image (5120 KB)
        $largeFile = UploadedFile::fake()->create('large.jpg', 5120, 'image/jpeg');

        // Compression service compressUnderLimit test directly
        $compressionService = app(ImageCompressionService::class);
        $result = $compressionService->compressUnderLimit($largeFile);

        $this->assertLessThanOrEqual(3 * 1024 * 1024, $result->sizeBytes);

        // Upload through controller endpoint
        $response = $this->postJson("{$this->baseUrl}/images", [
            'image' => $largeFile,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.cloudflare_id', 'cf-large-compressed-id');

        $uploadedRecord = UploadedImage::where('cloudflare_id', 'cf-large-compressed-id')->first();
        $this->assertNotNull($uploadedRecord);
        $this->assertLessThanOrEqual(3 * 1024 * 1024, $uploadedRecord->size_bytes);
    }

    public function test_updates_existing_image_and_deletes_old_from_cloudflare(): void
    {
        $deletedCloudflareIds = [];

        $mockService = $this->mock(CloudflareImageService::class);
        $mockService->shouldReceive('upload')
            ->once()
            ->andReturn(new CloudflareImageUploadResult(
                id: 'cf-new-replaced-id',
                url: 'https://imagedelivery.net/account-hash/cf-new-replaced-id/public',
                variants: [
                    'https://imagedelivery.net/account-hash/cf-new-replaced-id/public',
                ],
                filename: 'new.jpg',
            ));
        $mockService->shouldReceive('delete')
            ->once()
            ->with('cf-old-id')
            ->andReturnUsing(function ($id) use (&$deletedCloudflareIds) {
                $deletedCloudflareIds[] = $id;

                return true;
            });

        $existing = UploadedImage::create([
            'url' => 'https://imagedelivery.net/account-hash/cf-old-id/public',
            'cloudflare_id' => 'cf-old-id',
            'filename' => 'old.jpg',
            'size_bytes' => 102400,
            'mime_type' => 'image/jpeg',
        ]);

        $newFile = UploadedFile::fake()->image('new.jpg', 400, 400);

        $response = $this->postJson("{$this->baseUrl}/images/{$existing->id}", [
            'image' => $newFile,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('data.cloudflare_id', 'cf-new-replaced-id');

        // Check old ID was sent to Cloudflare DELETE
        $this->assertContains('cf-old-id', $deletedCloudflareIds);

        // Check DB was updated with new image
        $existing->refresh();
        $this->assertEquals('cf-new-replaced-id', $existing->cloudflare_id);
    }

    public function test_destroys_image_deletes_from_cloudflare_and_removes_from_database(): void
    {
        $deletedCloudflareIds = [];

        $mockService = $this->mock(CloudflareImageService::class);
        $mockService->shouldReceive('delete')
            ->once()
            ->with('cf-to-delete-123')
            ->andReturnUsing(function ($id) use (&$deletedCloudflareIds) {
                $deletedCloudflareIds[] = $id;

                return true;
            });

        $image = UploadedImage::create([
            'url' => 'https://imagedelivery.net/account-hash/cf-to-delete-123/public',
            'cloudflare_id' => 'cf-to-delete-123',
            'filename' => 'delete_me.jpg',
            'size_bytes' => 204800,
            'mime_type' => 'image/jpeg',
        ]);

        $response = $this->deleteJson("{$this->baseUrl}/images/{$image->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'deleted' => true,
                'id' => $image->id,
            ],
        ]);

        $this->assertContains('cf-to-delete-123', $deletedCloudflareIds);
        $this->assertDatabaseMissing('uploaded_images', ['id' => $image->id]);
    }

    public function test_rejects_non_image_files(): void
    {
        $textDoc = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->postJson("{$this->baseUrl}/images", [
            'image' => $textDoc,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'status',
            'code',
            'message',
            'data' => [
                'image',
            ],
        ]);
    }

    public function test_lists_and_shows_images(): void
    {
        $img = UploadedImage::create([
            'url' => 'https://imagedelivery.net/account/img-item-1/public',
            'cloudflare_id' => 'img-item-1',
            'filename' => 'item1.png',
            'size_bytes' => 50000,
            'mime_type' => 'image/png',
        ]);

        $listRes = $this->getJson("{$this->baseUrl}/images");
        $listRes->assertStatus(200);
        $this->assertEquals(1, $listRes->json('data.meta.total'));

        $showRes = $this->getJson("{$this->baseUrl}/images/{$img->id}");
        $showRes->assertStatus(200);
        $this->assertEquals('img-item-1', $showRes->json('data.cloudflare_id'));
    }
}
