<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class CompressedImageResult
{
    public function __construct(
        public string $filePath,
        public int $sizeBytes,
        public string $mimeType,
        public string $filename,
        public bool $isTemporary = false,
    ) {
    }
}

