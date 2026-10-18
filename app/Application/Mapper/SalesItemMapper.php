<?php

namespace App\Application\Mapper;

use App\Domain\Entities\SalesItemEntity;
use Illuminate\Database\Eloquent\Model;

class SalesItemMapper
{
    public static function modelToEntity($model)
    {
        $productEntity = ($model instanceof Model && $model->relationLoaded('product') && $model->product)
            ? ProductMapper::modelToEntity($model->product)
            : null;

        return new SalesItemEntity(
            product_id: $model->product_id,
            quantity: $model->quantity,
            id: $model->id,
            bill_id: $model->bill_id,
            price: $model->price,
            total: $model->total,
            product: $productEntity,
            product_code: $productEntity?->code,
        );
    }
}
