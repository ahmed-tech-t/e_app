<?php

namespace App\Application\Mapper;

use App\Domain\Entities\PurchaseItemEntity;
use Illuminate\Database\Eloquent\Model;

class PurchaseItemMapper
{
    public static function modelToEntity($model)
    {
        $productEntity = ($model instanceof Model && $model->relationLoaded('product') && $model->product)
            ? ProductMapper::modelToEntity($model->product)
            : null;

        return new PurchaseItemEntity(
            product_id: $model['product_id'],
            quantity: $model['quantity'],
            price: $model['price'],
            purchase_id: $model['purchase_id'],
            id: $model['id'],
            total: $model['total'],
            product: $productEntity,
            product_code: $productEntity?->code,
        );
    }
}