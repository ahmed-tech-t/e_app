<?php

namespace App\Application\Mapper;

use App\Domain\Entities\StockMovementEntity;

class StockMovementMapper
{
    public static function toEntity($data)
    {
        return new StockMovementEntity(
            id: $data['id'],
            productBatchId: $data['product_batch_id'],
            locationId: $data['location_id'],
            quantity: $data['quantity'],
            type: $data['type'],
            billNumber: $data['bill_number'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
        );
    }
}