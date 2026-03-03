<?php

namespace App\Application\DTOs;

use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\utils\StockMovementType;

class StockMovementSearchDto
{
    public function __construct(
        public ?int $product_id,
        public ?int $location_id,
        public ?int $product_batch_id,
        public ?StockMovementType $type,
        public ?string $bill_number,
    ) {
    }
    public function toArray()
    {
        return [
            'product_id' => $this->product_id,
            'location_id' => $this->location_id,
            'product_batch_id' => $this->product_batch_id,
            'type' => $this->type,
            'bill_number' => $this->bill_number
        ];
    }
}