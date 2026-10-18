<?php

namespace App\Application\Mapper;

use App\Application\DTOs\PurchaseReturnItemDto;
use App\Domain\Entities\PurchaseItemEntity;
use App\Domain\Entities\PurchaseReturnItemEntity;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItemMapper
{
    public static function modelToEntity($model)
    {
        $productEntity = ($model instanceof Model && $model->relationLoaded('product') && $model->product)
            ? ProductMapper::modelToEntity($model->product)
            : null;

        return new PurchaseReturnItemEntity(
            id: $model->id,
            purchase_return_id: $model->purchase_return_id,
            product_id: $model->product_id,
            quantity: $model->quantity,
            price: $model->price,
            total: $model->total,
            reason: $model->reason,
            restock_status: $model->restock_status,
            product: $productEntity,
            product_code: $productEntity?->code,
        );
    }

    public static function dtoToEntity(PurchaseReturnItemDto $dto, PurchaseItemEntity $originalItem)
    {
        return PurchaseReturnItemEntity::create(
            data: $dto->toArray(),
            price: $originalItem->price
        );
    }

    public static function mapItemsFromDto(array $dtoItems, array $originalItems)
    {
        return collect($dtoItems)->map(function ($item) use ($originalItems) {
            $originalItem = collect($originalItems)->firstWhere('product_id', $item->product_id);

            return self::dtoToEntity($item, $originalItem);
        })->all();
    }
}
