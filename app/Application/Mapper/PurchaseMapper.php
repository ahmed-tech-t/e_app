<?php

namespace App\Application\Mapper;

use App\Domain\Entities\PurchaseEntity;

class PurchaseMapper
{
    public static function modelToEntity($model)
    {
        return new PurchaseEntity(
            id: $model['id'],
            code: $model['code'],
            store_id: $model['store_id'],
            supplier_id: $model['supplier_id'],
            total: $model['total'],
            discount: $model['discount'],
            tax: $model['tax'],
            grand_total: $model['grand_total'],
            created_at: $model['created_at'],
            items: collect($model->relationLoaded('items') ? $model->items : [])->map(
                fn($item) => PurchaseItemMapper::modelToEntity($item)
            )->all(),
        );
    }
}