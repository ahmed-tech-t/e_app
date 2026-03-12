<?php

namespace App\Application\DTOs;



class PurchaseDto
{

    public function __construct(
        public ?int $supplier_id = null,
        public ?int $store_id = null,
        public float $discount = 0,
        public float $tax = 0,
        /** @var PurchaseItemDto[] */
        public ?array $items,
        public float $paper_total
    ) {
    }


    public function toArray()
    {
        return [
            'supplier_id' => $this->supplier_id,
            'store_id' => $this->store_id,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'paper_total' => $this->paper_total,
            'items' => $this->items,
        ];
    }
}