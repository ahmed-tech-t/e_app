<?php

namespace App\Application\Mapper;

use App\Application\DTOs\CreateProductDto;
use App\Application\DTOs\UpdateProductDto;
use App\Domain\Entities\ProductEntity;
use App\Infrastructure\Persistence\Models\Product;

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
            categoryId: $model->category_id,


            category: $model->relationLoaded('category')
            ? CategoryMapper::modelToEntity($model->category) : null,

            description: $model->description,
            brand: $model->brand,
            image: $model->image,
            origin: $model->origin,
            unitsPerCarton: $model->units_per_carton,
            saleUnitId: $model->sale_unit_id,
            saleUnit: $model->relationLoaded('saleUnit')
            ? SaleUnitMapper::modelToEntity($model->saleUnit) : null,
            created_at: $model->created_at,
            updated_at: $model->updated_at
        );
    }


}