<?php

namespace App\Application\DTOs;


class ProductBatchDto
{

    public function __construct(
        public ?int $locationId = null,
        public ?int $productId = null,
        public ?string $batchCode = null,
        public ?int $initialQuantity = null,
        public ?float $costPrice = null,

    ) {

    }


    public function toArray()
    {
        return [
            'location_id' => $this->locationId,
            'batch_code' => $this->batchCode,
            'product_id' => $this->productId,
            'initial_quantity' => $this->initialQuantity,
            'cost_price' => $this->costPrice
        ];
    }
}