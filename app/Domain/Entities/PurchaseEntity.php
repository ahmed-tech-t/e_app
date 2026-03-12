<?php

namespace App\Domain\Entities;

use App\Infrastructure\Persistence\utils\Constants;
use App\Traits\TotalCalc;
use Carbon\Carbon;


class PurchaseEntity
{
    use TotalCalc;

    public function __construct(
        public ?int $id = null,
        public ?string $code = null,
        public ?int $store_id = null,
        public ?int $supplier_id = null,
        public ?float $total = null,
        public ?float $discount = null,
        public ?float $tax = null,
        public ?float $grand_total = null,
        /** @var PurchaseItemEntity[] */
        public ?array $items = [],
        public ?Carbon $created_at = null
    ) {
    }

    public static function create(array $data, $items)
    {
        $discount = $data['discount'] ?? 0;

        $total = self::getTotal($items);

        $grandTotal = self::getGrandTotal($total, $discount, $data['tax']);

        if (isset($data['paper_total']) && abs($data['paper_total'] - $grandTotal) > 0.01) {
            throw new \DomainException("Error: The paper invoice total does not match the system calculations. the system total is {$grandTotal} and the paper total is {$data['paper_total']}");
        }
        return new self(
            store_id: $data['store_id'],
            supplier_id: $data['supplier_id'],
            total: $total,
            discount: $discount,
            tax: $data['tax'],
            grand_total: $grandTotal,
            items: $items,
        );

    }
    public function update(array $data)
    {

    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'store_id' => $this->store_id,
            'supplier_id' => $this->supplier_id,
            'total' => $this->total,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'grand_total' => $this->grand_total,
        ];
    }
}