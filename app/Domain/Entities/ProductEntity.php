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

    public static function create(array $data): self
    {
        return new self(
            name_ar: $data['name_ar'],
            brand: $data['brand'],
            unitsPerCarton: $data['unitsPerCarton'],
            original_code: $data['original_code'],
            name_en: $data['name_en'],
            origin: $data['origin'],
            categoryId: $data['categoryId'],
            description: $data['description'],
            image: $data['image'],
            saleUnitId: $data['saleUnitId'],
        );
    }
    public function update(array $data)
    {
        $this->categoryId = $data['categoryId'] ?? $this->categoryId;

        $this->original_code = $data['original_code'] ?? $this->original_code;

        $this->name_ar = $data['name_ar'] ?? $this->name_ar;

        $this->name_en = $data['name_en'] ?? $this->name_en;

        $this->description = $data['description'] ?? $this->description;

        $this->brand = $data['brand'] ?? $this->brand;

        $this->image = $data['image'] ?? $this->image;

        $this->origin = $data['origin'] ?? $this->origin;

        $this->unitsPerCarton = $data['unitsPerCarton'] ?? $this->unitsPerCarton;

        $this->saleUnitId = $data['saleUnitId'] ?? $this->saleUnitId;

        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'code' => $this->code,
            'original_code' => $this->original_code,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'category_id' => $this->categoryId,
            'description' => $this->description,
            'brand' => $this->brand,
            'units_per_carton' => $this->unitsPerCarton,
            'sale_unit_id' => $this->saleUnitId,

        ];
        if ($this->image)
            $data['image'] = $this->image;

        if ($this->origin)
            $data['origin'] = $this->origin;

        return $data;
    }
}