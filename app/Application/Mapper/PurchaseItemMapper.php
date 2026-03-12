<?php

namespace App\Application\Mapper;

use App\Domain\Entities\purchaseItemEntity;

class PurchaseItemMapper
{
    public static function modelToEntity($model)
    {
        return new PurchaseItemEntity(
            id: $model['id'],
            product_id: $model['product_id'],
            quantity: $model['quantity'],
            purchase_id: $model['purchase_id'],
            price: $model['price'],
            total: $model['total'],
        );
    }
}