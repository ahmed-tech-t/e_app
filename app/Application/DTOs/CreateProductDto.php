<?php
namespace App\Application\DTOs;
class CreateProductDto
{
    public function __construct(
        public string $category_id,
        public string $original_code,
        public string $name_ar,
        public string $brand,
        public int $sale_unit_id,
        public int $units_per_carton,
        public float $retail_price,
        public float $wholesale_price,
        public ?string $name_en = null,
        public ?string $origin = null,
        public ?string $description = null,
        public ?string $image = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->category_id,
            'original_code' => $this->original_code,
            'name_ar' => $this->name_ar,
            'brand' => $this->brand,
            'sale_unit_id' => $this->sale_unit_id,
            'units_per_carton' => $this->units_per_carton,
            'name_en' => $this->name_en,
            'origin' => $this->origin,
            'description' => $this->description,
            'image' => $this->image,
            'retail_price' => $this->retail_price,
            'wholesale_price' => $this->wholesale_price
        ];
    }
}