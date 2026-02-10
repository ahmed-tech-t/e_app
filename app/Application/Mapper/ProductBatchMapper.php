<?php

namespace App\Application\Mapper;

use App\Domain\Entities\ProductBatchEntity;

class ProductBatchMapper
{
    public static function modelToEntity($model)
    {
        return new ProductBatchEntity(
            id: $model['id'],
            batchCode: $model['batch_code'],
            productId: $model['product_id'],
            initialQuantity: $model['initial_quantity'],
            remainingQuantity: $model['remaining_quantity'],
            costPrice: $model['cost_price'],
            retailPrice: $model['retail_price'],
            wholesalePrice: $model['wholesale_price'],

            product: $model->relationLoaded('product')
            ? self::mapProduct($model->product) : null,


            locations: $model->relationLoaded('locations')
            ? $model->locations->map(fn($loc) => self::mapLocation($loc))->toArray() : null,

            created_at: $model['created_at'],
            updated_at: $model['updated_at']
        );
    }


    private static function mapProduct($product)
    {
        return [
            'product_id' => $product->id,
            'product_code' => $product->code,
            'product_name' => $product->name_ar,
        ];
    }
    private static function mapLocation($location)
    {
        return [
            'location_id' => $location->id,
            'location_name' => $location->name,
            // Accessing data from the pivot table (standard for many-to-many)
            'remaining_quantity' => $location->pivot?->remaining_quantity ?? $location->remaining_quantity,
        ];
    }
}