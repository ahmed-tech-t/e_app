<?php

namespace App\Application\DTOs;

class PurchaseDto
{
    public function __construct(
        public ?int $supplier_id,
        public ?int $store_id,
        public float $discount,
        public float $tax,
        /** @var PurchaseItemDto[] */
        public ?array $items,
        public float $paper_total
    ) {}

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
