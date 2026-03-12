<?php

namespace App\Domain\Entities;



class PurchaseItemEntity
{

    public function __construct(
        public int $product_id,
        public int $quantity,
        public float $price,
        public ?int $purchase_id = null,
        public ?int $id = null,
        public ?float $total = null
    ) {

    }

    public static function create(array $data)
    {
        $total = round($data['quantity'] * $data['price'], 2);
        return new self(
            product_id: $data['product_id'],
            quantity: $data['quantity'],
            price: $data['price'],
            total: $total
        );

    }
    public function update(array $data)
    {
    }

    public function toArray()
    {
        return [
            'purchase_id' => $this->purchase_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->total,
        ];
    }
}