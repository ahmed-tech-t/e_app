<?php

namespace App\Application\DTOs\Stock;

use App\Infrastructure\Persistence\utils\StockMovementType;

class StockMovementSearchDto implements StockSearchInterfaceDto
{
    public function __construct(
        public ?int $product_id,
        public ?int $location_id,
        public ?StockMovementType $type,
        public ?string $bill_number,
        public ?int $product_batch_id = null,
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