<?php

namespace App\Application\Mapper;

use App\Application\DTOs\SalesReturnItemDto;
use App\Domain\Entities\SalesItemEntity;
use App\Domain\Entities\SalesReturnItemEntity;
use App\Domain\Utils\ReturnedItemStatus;
use App\Infrastructure\Persistence\Models\Sales;
use Illuminate\Support\Facades\Log;

class SalesReturnItemMapper
{
    public static function modelToEntity($model)
    {
        return new SalesReturnItemEntity(

            sales_return_id: $model->sales_return_id,
            product_id: $model->product_id,
            quantity: $model->quantity,
            price: $model->price,
            total: $model->total,
            reason: $model->reason,
            restock_status: $model->restock_status,
        );
    }

    public static function dtoToEntity(SalesReturnItemDto $dto, SalesItemEntity $originalItem)
    {

        return SalesReturnItemEntity::create(
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