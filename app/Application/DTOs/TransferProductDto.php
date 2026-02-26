<?php

namespace App\Application\DTOs;

class TransferProductDto
{


    public function __construct(
        public int $productId,
        public int $fromLocationId,
        public int $toLocationId,
        public int $quantity
    ) {
    }

    public function toArray()
    {
        return [
            'product_id' => $this->productId,
            'from_location_id' => $this->fromLocationId,
            'to_location_id' => $this->toLocationId,
            'quantity' => $this->quantity,
        ];
    }
}