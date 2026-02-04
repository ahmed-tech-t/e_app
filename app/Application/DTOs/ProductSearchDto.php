<?php

namespace App\Application\DTOs;

class ProductSearchDto
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $perPage = null,
        public readonly ?string $name_ar = null,
        public readonly ?string $name_en = null,
        public readonly ?string $brand = null,
        public readonly ?string $original_code = null,
        public readonly ?string $code = null,
        public readonly ?int $categoryId = null,
        public readonly ?int $saleUnitId = null,
    ) {
    }

}