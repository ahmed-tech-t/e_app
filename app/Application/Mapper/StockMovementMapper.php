<?php

namespace App\Application\Mapper;

use App\Domain\Entities\StockMovementEntity;

class StockMovementMapper
{
    public static function toEntity($data)
    {
        return new StockMovementEntity(
            id: $data->id,

            product_batch_id: $data->product_batch_id,
            location_id: $data->location_id,

            product_batch: $data->relationLoaded('batch') ? ProductBatchMapper::toEntity(
                $data->batch
            ) : null,

            location: $data->relationLoaded('location') ?
            LocationMapper::toEntity(
                $data->location
            ) : null,

            quantity: $data->quantity,
            type: $data->type,
            bill_number: $data->bill_number,
            created_at: $data->created_at,
        );
    }
}