<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTOs\CloudflareImageUploadResult;
use App\Application\DTOs\CompressedImageResult;
use Cloudinary\Cloudinary;
use RuntimeException;

class CloudflareImageService
{
    private ?Cloudinary $cloudinary = null;

    private string $folder;

    private bool $isProduction;

    public function __construct()
    {
        $this->isProduction = (bool) app()->isProduction();
        $this->folder = (string) config('services.cloudinary.folder', 'products');
    }

    private function getCloudinary(): Cloudinary
    {
        if ($this->cloudinary !== null) {
            return $this->cloudinary;
        }

        $cloudinaryUrl = config('services.cloudinary.url') ?: env('CLOUDINARY_URL');
        $cloudName = config('services.cloudinary.cloud_name') ?: env('CLOUDINARY_CLOUD_NAME');

        if (! empty($cloudinaryUrl)) {
            $this->cloudinary = new Cloudinary($cloudinaryUrl);
            $this->cloudinary->configuration->url->secure($this->isProduction);
        } elseif (! empty($cloudName)) {
            $this->cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => config('services.cloudinary.api_key'),
                    'api_secret' => config('services.cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => $this->isProduction,
                ],
            ]);
        } else {
            throw new RuntimeException('Cloudinary credentials are not configured.');
        }

        return $this->cloudinary;
    }

    /**
     * Upload an image to Cloudinary.
     */
    public function upload(CompressedImageResult $image): CloudflareImageUploadResult
    {
        $options = [
            'folder' => $this->folder,
            'resource_type' => 'image',
            'use_filename' => true,
            'unique_filename' => true,
        ];

        // Optional: Disable Guzzle SSL verification in non-production environments
        // if you are getting cURL error 60 (SSL certificate problem) locally.
        if (! $this->isProduction) {
            $options['verify'] = false;
        }

        $result = $this->getCloudinary()->uploadApi()->upload($image->filePath, $options);

        $publicId = (string) ($result['public_id'] ?? '');

        // Use 'secure_url' in production, allow 'url' (http) fallback in non-production
        $url = $this->isProduction
            ? (string) ($result['secure_url'] ?? '')
            : (string) ($result['url'] ?? $result['secure_url'] ?? '');

        if ($url === '') {
            throw new RuntimeException('Cloudinary upload returned no URL.');
        }

        return new CloudflareImageUploadResult(
            id: $publicId,
            url: $url,
            variants: [],
            filename: $image->filename,
        );
    }

    /**
     * Delete an image from Cloudinary by public_id or URL.
     */
    public function delete(string $publicIdOrUrl): bool
    {
        $publicId = $this->extractPublicId($publicIdOrUrl);
        if ($publicId === '') {
            return false;
        }

        $options = [
            'resource_type' => 'image',
        ];

        if (! $this->isProduction) {
            $options['verify'] = false;
        }

        try {
            $result = $this->getCloudinary()->uploadApi()->destroy($publicId, $options);

            return ($result['result'] ?? '') === 'ok';
        } catch (\Throwable) {
            return false;
        }
    }

    private function extractPublicId(string $identifier): string
    {
        // If it's a full URL, ensure it's a Cloudinary URL before attempting extraction
        if (str_starts_with($identifier, 'http://') || str_starts_with($identifier, 'https://')) {
            if (! str_contains($identifier, 'cloudinary.com')) {
                return '';
            }

            // e.g. https://res.cloudinary.com/dcgewtlhs/image/upload/v123/products/abc.jpg
            // public_id = products/abc
            $path = parse_url($identifier, PHP_URL_PATH) ?? '';
            // strip /image/upload/vXXX/ prefix
            $path = preg_replace('#^/[^/]+/image/upload/(v\d+/)?#', '', $path) ?? $path;

            // strip extension
            return (string) preg_replace('/\.[^.]+$/', '', $path);
        }

        return $identifier;
    }
}
