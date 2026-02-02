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

    public function toArray()
    {
        return [
            'categoryId' => $this->categoryId,
            'original_code' => $this->original_code,
            'name_ar' => $this->name_ar,
            'brand' => $this->brand,
            'saleUnitId' => $this->saleUnitId,
            'unitsPerCarton' => $this->unitsPerCarton,
            'name_en' => $this->name_en,
            'origin' => $this->origin,
            'description' => $this->description,
            'image' => $this->image,
        ];
    }
}