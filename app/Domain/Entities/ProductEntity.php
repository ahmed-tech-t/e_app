<?php
namespace App\Domain\Entities;

use Carbon\Carbon;


class ProductEntity
{
    public function __construct(
        public string $name_ar,
        public string $brand,
        public int $units_per_carton,
        public string $original_code,
        public ?float $retail_price = null,
        public ?float $wholesale_price = null,
        public ?string $name_en = null,
        public ?string $origin = null,
        public readonly ?int $id = null,
        public ?string $code = null,
        public ?int $category_id = null,
        public ?string $description = null,
        public ?string $image = null,
        public ?int $sale_unit_id = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
        public ?SaleUnitEntity $sale_unit = null,
        public ?CategoryEntity $category = null,
    ) {
    }

    public static function create(array $data): self
    {
        return new self(
            name_ar: $data['name_ar'],
            brand: $data['brand'],
            units_per_carton: $data['units_per_carton'],
            original_code: $data['original_code'],
            name_en: $data['name_en'],
            origin: $data['origin'],
            category_id: $data['category_id'],
            description: $data['description'],
            image: $data['image'],
            sale_unit_id: $data['sale_unit_id'],
            retail_price: $data['retail_price'],
            wholesale_price: $data['wholesale_price'],
        );
    }
    public function update(array $data)
    {
        $this->category_id = $data['category_id'] ?? $this->category_id;

        $this->original_code = $data['original_code'] ?? $this->original_code;

        $this->name_ar = $data['name_ar'] ?? $this->name_ar;

        $this->name_en = $data['name_en'] ?? $this->name_en;

        $this->description = $data['description'] ?? $this->description;

        $this->brand = $data['brand'] ?? $this->brand;

        $this->image = $data['image'] ?? $this->image;

        $this->origin = $data['origin'] ?? $this->origin;

        $this->units_per_carton = $data['units_per_carton'] ?? $this->units_per_carton;

        $this->sale_unit_id = $data['sale_unit_id'] ?? $this->sale_unit_id;

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'code' => $this->code,
            'original_code' => $this->original_code,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'brand' => $this->brand,
            'units_per_carton' => $this->units_per_carton,
            'sale_unit_id' => $this->sale_unit_id,

        ];
        if ($this->image)
            $data['image'] = $this->image;

        if ($this->origin)
            $data['origin'] = $this->origin;

        return $data;
    }
}