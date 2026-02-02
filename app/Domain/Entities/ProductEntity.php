<?php
namespace App\Domain\Entities;

use Carbon\Carbon;


class ProductEntity
{
    public function __construct(
        public string $name_ar,
        public string $brand,
        public int $unitsPerCarton,
        public string $original_code,
        public ?string $name_en = null,
        public ?string $origin = null,
        public readonly ?int $id = null,
        public ?string $code = null,
        public ?int $categoryId = null,
        public ?string $description = null,
        public ?string $image = null,
        public ?int $saleUnitId = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?SaleUnitEntity $saleUnit = null,
        public ?CategoryEntity $category = null,
    ) {
    }
}