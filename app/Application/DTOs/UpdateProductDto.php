<?php
namespace App\Application\DTOs;
class UpdateProductDto
{
    public function __construct(
        public ?string $categoryId = null,
        public ?string $original_code = null,
        public ?string $name_ar = null,
        public ?string $brand = null,
        public ?int $saleUnitId = null,
        public ?int $unitsPerCarton = null,
        public ?string $name_en = null,
        public ?string $origin = null,
        public ?string $description = null,
        public ?string $image = null,
    ) {
    }
}