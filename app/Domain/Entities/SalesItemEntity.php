<?php

namespace App\Domain\Entities;

use Carbon\Carbon;


class SalesItemEntity
{

    public function __construct(
        public ?int $product_id,
        public ?int $quantity,
        public ?int $id = null,
        public ?int $bill_id = null,
        public ?float $price = null,
        public ?float $total = null,
    ) {
    }

    public static function create(array $data, float $price)
    {
        $total = round($data['quantity'] * $price, 2);
        return new self(
            product_id: $data['product_id'],
            quantity: $data['quantity'],
            price: $price,
            total: $total,
        );
    }
    public function update(array $data)
    {
    }

    public function toArray()
    {
        return [
            'bill_id' => $this->bill_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
        ];
    }
}