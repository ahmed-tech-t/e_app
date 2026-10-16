<?php

declare(strict_types=1);

namespace App\Application\DTOs;

class CloudflareImageUploadResult
{
    public function __construct(
        public string $id,
        public string $url,
        public array $variants = [],
        public ?string $filename = null,
    ) {
    }
}

