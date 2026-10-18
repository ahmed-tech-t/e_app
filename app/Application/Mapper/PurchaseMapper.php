<?php

namespace App\Application\Mapper;

use App\Domain\Entities\PurchaseEntity;
use Illuminate\Database\Eloquent\Model;

class PurchaseMapper
{
    public static function modelToEntity($model)
    {
        $supplierEntity = ($model instanceof Model && $model->relationLoaded('supplier') && $model->supplier)
            ? SupplierMapper::modelToEntity($model->supplier)
            : null;
        $storeModel = ($model instanceof Model && ($model->relationLoaded('store') || $model->relationLoaded('location')))
            ? ($model->store ?? $model->location)
            : null;
        $storeEntity = $storeModel ? LocationMapper::modelToEntity($storeModel) : null;

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
                fn ($item) => PurchaseItemMapper::modelToEntity($item)
            )->all(),
            supplier: $supplierEntity,
            store: $storeEntity,
            location: $storeEntity,
            supplier_code: $supplierEntity?->code,
            store_code: $storeEntity?->code,
        );
    }
}
