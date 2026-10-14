<?php

namespace App\Application\DTOs;

use App\Domain\Utils\ReturnedItemStatus;

class PurchaseReturnItemDto
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        public ReturnedItemStatus $restock_status,
        public ?string $reason = null,
    ) {}

    public function toArray()
    {
        return [
            'product_id'     => $this->product_id,
            'quantity'       => $this->quantity,
            'reason'         => $this->reason,
            'restock_status' => $this->restock_status->value,
        ];
    }

    public static function create(array $data): self
    {
        return new self(
            product_id: $data['product_id'],
            quantity: $data['quantity'],
            reason: $data['reason'] ?? null,
            restock_status: ReturnedItemStatus::tryFrom($data['restock_status']) ?? ReturnedItemStatus::RESEALABLE,
        );
    }
}
