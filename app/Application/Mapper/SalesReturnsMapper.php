<?php

namespace App\Application\Mapper;


use App\Application\DTOs\SalesReturnsDto;
use App\Domain\Entities\SalesEntity;
use App\Domain\Entities\SalesReturnsEntity;


class SalesReturnsMapper
{
    public static function modelToEntity($model)
    {
        return new SalesReturnsEntity(
            id: $model['id'],
            code: $model['code'],
            store_id: $model['location_id'],
            sales_id: $model['original_bill_id'],
            discount: $model['discount'],
            tax: $model['tax'],
            grand_total: $model['grand_total'],
            total: $model['total'],
            created_at: $model['created_at'],
        );
    }

    public static function dtoToEntity(SalesReturnsDto $dto, SalesEntity $originalBill)
    {
        return SalesReturnsEntity::create(
            $dto->toArray(),
            SalesReturnItemMapper::mapItemsFromDto(
                $dto->items,
                $originalBill->items
            ),
            $originalBill->discount ?? 0,
            $originalBill->tax ?? 0
        );
    }
}