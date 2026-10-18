<?php

namespace App\Application\DTOs;

class PurchaseReturnDto
{
    public function __construct(
        public int $purchase_id,
        public int $store_id,
        /** @var PurchaseReturnItemDto[] */
        public array $items,
    ) {}

    public function toArray()
    {
        return [
            'purchase_id' => $this->purchase_id,
            'store_id' => $this->store_id,
            'items' => $this->items,
        ];
    }
}
