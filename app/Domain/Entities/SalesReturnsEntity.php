<?php

namespace App\Domain\Entities;

use App\Traits\TotalCalc;
use Carbon\Carbon;


class SalesReturnsEntity
{

    use TotalCalc;
    public function __construct(
        public ?int $id = null,
        public ?string $code = null,
        public ?string $original_bill_code = null,
        public ?int $sales_id = null,
        public ?int $store_id = null,
        public ?float $total = null,
        public ?float $discount = null,
        public ?float $tax = null,
        public ?float $grand_total = null,
        public ?array $items = [],

        public ?Carbon $created_at = null,
    ) {
    }

    public static function create(array $data, array $items, float $discount, float $tax)
    {

        $total = self::getTotal($items);

        $grandTotal = self::getGrandTotal($total, $discount, $tax);

        return new self(

            sales_id: $data['sales_id'] ?? null,
            store_id: $data['store_id'],
            total: $total,
            discount: $discount,
            tax: $tax,
            grand_total: $grandTotal,
            items: $items,
        );
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'location_id' => $this->store_id,
            'original_bill_id' => $this->sales_id,
            'total' => $this->total,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'grand_total' => $this->grand_total
        ];
    }



}