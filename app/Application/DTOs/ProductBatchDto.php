<?php

namespace App\Application\DTOs;


class ProductBatchDto
{

    public function __construct(
        public ?int $location_id = null,
        public ?int $product_id = null,
        public ?string $batch_code = null,
        public ?int $initial_quantity = null,
        public ?float $cost_price = null,

    ) {

    }


    public function toArray()
    {
        return [
            'location_id' => $this->location_id,
            'batch_code' => $this->batch_code,
            'product_id' => $this->product_id,
            'initial_quantity' => $this->initial_quantity,
            'cost_price' => $this->cost_price
        ];
    }
}