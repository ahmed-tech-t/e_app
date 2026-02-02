<?php
namespace App\Application\DTOs;
class CreateProductDto
{
    public function __construct(
        public string $categoryId,
        public string $original_code,
        public string $name_ar,
        public string $brand,
        public int $saleUnitId,
        public int $unitsPerCarton,
        public ?string $name_en = null,
        public ?string $origin = null,
        public ?string $description = null,
        public ?string $image = null,
    ) {
    }
}