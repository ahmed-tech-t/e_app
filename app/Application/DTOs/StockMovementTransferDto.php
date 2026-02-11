<?php

namespace App\Application\DTOs;

class StockMovementTransferDto
{
    public function __construct(
        public ?int $fromLocationId = null,
        public ?int $toLocationId = null,
        public ?int $batchId = null,
        public ?int $quantity = null,
    ) {
    }


    public function toArray()
    {
        return [
            'from_location_id' => $this->fromLocationId,
            'to_location_id' => $this->toLocationId,
            'batch_id' => $this->batchId,
            'quantity' => $this->quantity,
        ];
    }
}