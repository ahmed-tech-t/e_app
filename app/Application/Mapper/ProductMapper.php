<?php

namespace App\Application\Mapper;

use App\Domain\Entities\ProductEntity;

use Illuminate\Support\Facades\Log;

class ProductMapper
{
    public static function modelToEntity($model)
    {
        return new ProductEntity(
            id: $model->id,
            code: $model->code,
            original_code: $model->original_code,
            name_ar: $model->name_ar,
            name_en: $model->name_en,
            category_id: $model->category_id,

            category: $model->relationLoaded('category')
            ? CategoryMapper::modelToEntity($model->category) : null,

            retail_price: $model->retail_price ?? null,

            wholesale_price: $model->wholesale_price ?? null,

            quantity: $model->quantity ?? null,

            description: $model->description,
            brand: $model->brand,
            image: $model->image,
            origin: $model->origin,
            units_per_carton: $model->units_per_carton,
            sale_unit_id: $model->sale_unit_id,
            sale_unit: $model->relationLoaded('saleUnit')
            ? SaleUnitMapper::modelToEntity($model->saleUnit) : null,
            created_at: $model->created_at,
            updated_at: $model->updated_at
        );
    }

    private function extractPrice($data)
    {
        return $data->price;
    }
}