<?php

namespace App\Domain\Entities;

use App\Domain\Utils\ReturnedItemStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesReturnItemEntity
{

    public function __construct(
        public ?int $product_id,
        public ?int $quantity,

        public ?string $reason = null,
        public ?ReturnedItemStatus $restock_status = null,
        public ?int $id = null,
        public ?int $sales_return_id = null,
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
            reason: $data['reason'] ?? null,
            restock_status: ReturnedItemStatus::tryFrom($data['restock_status']),
            price: $price,
            total: $total,
        );
    }


    public function toArray()
    {
        return [
            'sales_return_id' => $this->sales_return_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
            'restock_status' => $this->restock_status?->value,
            'price' => $this->price,
            'total' => $this->total,
        ];
    }
}