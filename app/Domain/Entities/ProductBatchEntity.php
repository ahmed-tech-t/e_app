<?php

namespace App\Domain\Entities;

use Carbon\Carbon;


class ProductBatchEntity
{

    public function __construct(
        public int $productId,
        public string $batchCode,
        public int $initialQuantity,
        public int $remainingQuantity,
        public float $costPrice,
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
            batchCode: $data['batch_code'],
            productId: $data['product_id'],
            initialQuantity: $data['initial_quantity'],
            remainingQuantity: $data['initial_quantity'],
            costPrice: $data['cost_price'],
        );
    }
    public function update(array $data)
    {
        $this->costPrice = $data['cost_price'] ?? $this->costPrice;

        return $this;
    }

    public function toArray()
    {
        return [
            'batch_code' => $this->batchCode,
            'product_id' => $this->productId,
            'cost_price' => $this->costPrice
        ];
    }
}