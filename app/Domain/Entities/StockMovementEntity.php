<?php

namespace App\Domain\Entities;

use Carbon\Carbon;


class StockMovementEntity
{
    public function __construct(
        public int $id,
        public int $productBatchId,
        public int $locationId,
        public float $quantity,
        public string $type,
        public Carbon $createdAt,
        public Carbon $updatedAt,
        public ?string $billNumber = null,
    ) {

    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'product_batch_id' => $this->productBatchId,
            'location_id' => $this->locationId,
            'quantity' => $this->quantity,
            'type' => $this->type,
            'bill_number' => $this->billNumber
        ];
    }
}