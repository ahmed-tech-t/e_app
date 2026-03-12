<?php

namespace App\Domain\Entities;

use App\Infrastructure\Persistence\utils\StockMovementType;
use Carbon\Carbon;


class ProductBatchEntity
{

    public function __construct(
        public int $product_id,
        public string $batch_code,
        public int $initial_quantity,
        public int $remaining_quantity,
        public float $cost_price,
        public ?int $id = null,
        public ?array $locations = null,
        public ?array $product = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    ) {
    }

    public static function create(array $data)
    {
        return new self(
            batch_code: $data['batch_code'],
            product_id: $data['product_id'],
            initial_quantity: $data['initial_quantity'],
            remaining_quantity: $data['initial_quantity'],
            cost_price: $data['cost_price'],
        );
    }
    public function update(array $data)
    {
        $this->cost_price = $data['cost_price'] ?? $this->cost_price;

        return $this;
    }

    public function updateQuantity($type, $quantity)
    {
        if ($type == StockMovementType::ADJUST_INITIAL || $type == StockMovementType::ENTRY) {
            $this->initial_quantity += $quantity;
        }
        $this->remaining_quantity += $quantity;
    }

    public function toArray()
    {
        return [
            'batch_code' => $this->batch_code,
            'product_id' => $this->product_id,
            'cost_price' => $this->cost_price
        ];
    }
}