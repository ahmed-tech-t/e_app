<?php

namespace App\Application\Mapper;

use App\Domain\Entities\SalesItemEntity;

class SalesItemMapper
{
    public static function modelToEntity($model)
    {
        return new SalesItemEntity(
            id: $model->id,
            product_id: $model->product_id,
            quantity: $model->quantity,
            bill_id: $model->bill_id,
            price: $model->price,
            total: $model->total
        );
    }
}