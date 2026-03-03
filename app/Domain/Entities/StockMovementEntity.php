<?php

namespace App\Domain\Entities;

use App\Infrastructure\Persistence\Models\Location;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Carbon\Carbon;


class StockMovementEntity
{
    public function __construct(
        public int $id,
        public int $product_batch_id,
        public int $location_id,
        public float $quantity,
        public StockMovementType $type,
        public Carbon $created_at,
        public ?LocationEntity $location = null,
        public ?ProductBatchEntity $product_batch = null,
        public ?string $bill_number = null,
    ) {

    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'product_batch_id' => $this->product_batch_id,
            'location_id' => $this->location_id,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'bill_number' => $this->bill_number
        ];
    }
}