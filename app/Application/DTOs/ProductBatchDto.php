<?php

namespace App\Application\DTOs;

use Ramsey\Uuid\Type\Decimal;

class ProductBatchDto
{

    public function __construct(
        public ?int $productId = null,
        public ?string $batchCode = null,
        public ?int $initialQuantity = null,
        public ?int $remainingQuantity = null,
        public ?float $costPrice = null,
        public ?float $retailPrice = null,
        public ?float $wholesalePrice = null
    ) {

    }


    public function toArray()
    {
        return [
            'batch_code' => $this->batchCode,
            'product_id' => $this->productId,
            'remaining_quantity' => $this->remainingQuantity,
            'initial_quantity' => $this->initialQuantity,
            'cost_price' => $this->costPrice,
            'retail_price' => $this->retailPrice,
            'wholesale_price' => $this->wholesalePrice
        ];
    }
}