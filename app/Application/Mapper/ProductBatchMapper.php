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
            created_at: $model['created_at'],
            updated_at: $model['updated_at']
        );
    }
}