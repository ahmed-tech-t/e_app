<?php

namespace App\Application\DTOs;

use App\Infrastructure\Persistence\utils\PriceType;



class UpdateProductPriceDto
{

    public function __construct(
        public int $product_id,
        public float $price,
        public PriceType $type
    ) {
    }


    public function toArray()
    {
        return [
            'product_id' => $this->product_id,
            'price' => $this->price,
            'type' => $this->type
        ];
    }
}