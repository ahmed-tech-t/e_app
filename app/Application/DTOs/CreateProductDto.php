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

    public function toArray(): array
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