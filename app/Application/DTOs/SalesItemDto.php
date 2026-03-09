<?php

namespace App\Application\DTOs;



class SalesItemDto
{

    public function __construct(
        public int $product_id,
        public int $quantity
    ) {

    }


    public function toArray()
    {
        return [
            'product_id' => $this->product_id,
            'quantity' => $this->quantity
        ];
    }
    public static function create($data)
    {
        return new self(
            product_id: $data['product_id'],
            quantity: $data['quantity']
        );
    }
}