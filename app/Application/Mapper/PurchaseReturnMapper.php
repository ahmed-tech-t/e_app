<?php

namespace App\Application\Mapper;

use App\Application\DTOs\PurchaseReturnDto;
use App\Domain\Entities\PurchaseEntity;
use App\Domain\Entities\PurchaseReturnEntity;

class PurchaseReturnMapper
{
    public static function modelToEntity($model)
    {
        return new PurchaseReturnEntity(
            id: $model['id'],
            code: $model['code'],
            store_id: $model['location_id'],
            purchase_id: $model['original_bill_id'],
            discount: $model['discount'],
            tax: $model['tax'],
            grand_total: $model['grand_total'],
            total: $model['total'],
            created_at: $model['created_at'],
            items: collect($model->relationLoaded('items') ? $model->items : [])->map(
                fn($item) => PurchaseReturnItemMapper::modelToEntity($item)
            )->all(),
        );
    }

    public static function dtoToEntity(PurchaseReturnDto $dto, PurchaseEntity $originalBill)
    {
        return PurchaseReturnEntity::create(
            $dto->toArray(),
            PurchaseReturnItemMapper::mapItemsFromDto(
                $dto->items,
                $originalBill->items
            ),
            $originalBill->discount ?? 0,
            $originalBill->tax ?? 0
        );
    }
}
