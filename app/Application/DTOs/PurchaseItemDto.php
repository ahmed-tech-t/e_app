<?php

namespace App\Application\DTOs;



class PurchaseItemDto
{

    public function __construct(
        public int $product_id,
        public int $quantity,
        public float $price
    ) {

    }


    public function toArray()
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}