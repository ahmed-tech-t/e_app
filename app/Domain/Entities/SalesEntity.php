<?php

namespace App\Domain\Entities;

use App\Infrastructure\Persistence\utils\Constants;
use Carbon\Carbon;


class SalesEntity
{

    public function __construct(
        public string $customer_name,
        public ?float $total,
        public ?float $discount,
        public ?float $tax,
        public ?float $grand_total,
        /** @var SalesItemEntity[] */
        public array $items = [],
        public ?int $id = null,
        public ?Carbon $created_at = null,
        public ?string $customer_phone = null,
        public ?string $code = null,
        public ?LocationEntity $store = null,
        public ?int $store_id = null,
    ) {
    }

    public static function create(array $data, $items)
    {
        $discount = $data['discount'] ?? 0;

        $total = self::getTotal($items);

        $grandTotal = self::getGrandTotal($total, $discount);

        return new self(
            customer_name: $data['customer_name'],
            customer_phone: $data['customer_phone'] ?? null,
            total: $total,
            discount: $discount,
            tax: Constants::TAX,
            grand_total: $grandTotal,
            store_id: $data['store_id'],
            items: $items,
        );

    }


    public function toArray()
    {
        return [
            'code' => $this->code,
            'store_id' => $this->store_id,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'total' => $this->total,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'grand_total' => $this->grand_total,
        ];
    }

    private static function getTotal(array $items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item->total;
        }
        return round($total, 2);
    }

    private static function getGrandTotal(float $total, float $discount)
    {
        $discount = $discount * $total;
        $tax = (Constants::TAX / 100) * $total;
        return round($total - ($discount / 100) + $tax, 2);
    }
}