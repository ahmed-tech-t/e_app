<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Infrastructure\Persistence\Models\Product;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfInstance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class ProductCatalogService
{
    /**
     * Get distinct brand names from active products.
     *
     * @return array<int, string>
     */
    public function getBrands(): array
    {
        return Product::query()
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->toArray();
    }

    /**
     * Get products matching category, brand, or specific IDs for catalog extraction.
     *
     * @param  array<int, int>|null  $productIds
     */
    public function getProductsForCatalog(?int $categoryId = null, ?string $brand = null, ?array $productIds = null): Collection
    {
        $query = Product::with(['category', 'saleUnit'])
            ->withPrices();

        if (! empty($productIds)) {
            $query->whereIn('products.id', $productIds);
        } else {
            if ($categoryId) {
                $query->where('products.category_id', $categoryId);
            }
            if ($brand !== null && $brand !== '') {
                $query->where('products.brand', $brand);
            }
        }

        return $query->orderBy('products.name_ar')->get();
    }

    /**
     * Resolve absolute file path or URL for a product's image for Dompdf.
     */
    public function resolveImagePath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $normalized = str_replace('\\', '/', $path);

        // Discard temporary scratch paths
        if (str_contains($normalized, '/tmp/') || str_contains($normalized, 'Temp/')) {
            return null;
        }

        // External URLs
        if (str_starts_with($normalized, 'http://') || str_starts_with($normalized, 'https://')) {
            return $normalized;
        }

        $resolvedPath = null;

        if (file_exists($path) && is_file($path)) {
            $resolvedPath = $path;
        } elseif (Storage::disk('public')->exists($normalized)) {
            $resolvedPath = Storage::disk('public')->path($normalized);
        } elseif (file_exists(public_path($normalized)) && is_file(public_path($normalized))) {
            $resolvedPath = public_path($normalized);
        } elseif (file_exists(public_path('storage/'.ltrim($normalized, '/'))) && is_file(public_path('storage/'.ltrim($normalized, '/')))) {
            $resolvedPath = public_path('storage/'.ltrim($normalized, '/'));
        } elseif (file_exists(storage_path('app/public/'.ltrim($normalized, '/'))) && is_file(storage_path('app/public/'.ltrim($normalized, '/')))) {
            $resolvedPath = storage_path('app/public/'.ltrim($normalized, '/'));
        }

        if (! $resolvedPath || ! file_exists($resolvedPath) || ! is_file($resolvedPath)) {
            return null;
        }

        $resolvedPath = realpath($resolvedPath) ?: $resolvedPath;

        $ext = strtolower(pathinfo($resolvedPath, PATHINFO_EXTENSION));
        if ($ext === 'png' || $ext === 'webp') {
            return $this->ensureJpegImage($resolvedPath);
        }

        return str_replace('\\', '/', $resolvedPath);
    }

    /**
     * Ensure PNG or WebP images are converted to JPEG to prevent GD extension errors and optimize memory.
     */
    protected function ensureJpegImage(string $imagePath): ?string
    {
        $cacheDir = storage_path('framework/cache/catalog_images');
        if (! file_exists($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $hash = md5($imagePath.(string) filemtime($imagePath));
        $cachedJpg = $cacheDir.'/'.$hash.'.jpg';

        if (file_exists($cachedJpg)) {
            return str_replace('\\', '/', $cachedJpg);
        }

        if (class_exists('Imagick')) {
            try {
                $imagick = new \Imagick($imagePath);
                $imagick->setImageBackgroundColor('white');
                $flattened = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
                $flattened->setImageFormat('jpeg');
                $flattened->setImageCompressionQuality(85);
                $flattened->writeImage($cachedJpg);
                $flattened->clear();
                $flattened->destroy();
                $imagick->clear();
                $imagick->destroy();

                return str_replace('\\', '/', $cachedJpg);
            } catch (\Throwable) {
                // Fallback below
            }
        }

        if (function_exists('imagecreatefrompng') && function_exists('imagejpeg')) {
            try {
                $img = @imagecreatefrompng($imagePath);
                if ($img) {
                    $w = imagesx($img);
                    $h = imagesy($img);
                    $bg = imagecreatetruecolor($w, $h);
                    $white = imagecolorallocate($bg, 255, 255, 255);
                    imagefilledrectangle($bg, 0, 0, $w, $h, $white);
                    imagecopy($bg, $img, 0, 0, 0, 0, $w, $h);
                    imagejpeg($bg, $cachedJpg, 85);
                    imagedestroy($img);
                    imagedestroy($bg);

                    return str_replace('\\', '/', $cachedJpg);
                }
            } catch (\Throwable) {
                // Fallback below
            }
        }

        return str_replace('\\', '/', $imagePath);
    }

    /**
     * Generate the PDF catalog for the given collection of products.
     */
    public function generatePdf(Collection $products, string $priceType = 'retail'): DomPdfInstance
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $arabic = new Arabic;

        $catalogItems = [];
        foreach ($products as $product) {
            $title = (string) ($product->name_ar ?: ($product->name_en ?: $product->code));
            $brand = $product->brand ? (string) $product->brand : null;
            $catName = $product->category ? (string) ($product->category->name_ar ?: $product->category->name_en) : null;
            $size = $product->size ? (string) $product->size : null;
            $unitName = $product->saleUnit ? (string) ($product->saleUnit->name_ar ?: $product->saleUnit->name_en) : null;

            $catalogItems[] = [
                'model' => $product,
                'title' => @$arabic->utf8Glyphs($title, 100, false),
                'code' => $product->code,
                'original_code' => $product->original_code,
                'brand' => $brand ? @$arabic->utf8Glyphs($brand, 100, false) : null,
                'category' => $catName ? @$arabic->utf8Glyphs($catName, 100, false) : null,
                'size' => $size ? @$arabic->utf8Glyphs($size, 100, false) : null,
                'unit' => $unitName ? @$arabic->utf8Glyphs($unitName, 100, false) : null,
                'retail_price' => $product->retail_price,
                'wholesale_price' => $product->wholesale_price,
                'image_path' => $this->resolveImagePath($product->image),
            ];
        }

        unset($products);
        gc_collect_cycles();

        return Pdf::loadView('products.catalog-pdf', [
            'products' => $catalogItems,
            'arabic' => $arabic,
            'priceType' => $priceType,
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'defaultFont' => 'serif',
                'chroot' => base_path(),
            ]);
    }
}
