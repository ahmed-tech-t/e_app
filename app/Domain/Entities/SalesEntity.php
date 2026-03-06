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
        public array $items = [],
        public ?int $id = null,
        public ?Carbon $created_at = null,
        public ?string $customer_phone = null,
        public ?string $code = null,
        public ?LocationEntity $store = null,
        public ?int $store_id = null,
    ) {
    }

    public static function create(array $data)
    {
        $discount = $data['discount'] ?? 0;
        $total = self::getTotal($data['items']);
        $grandTotal = self::getGrandTotal($total, $discount);
        return new self(
            customer_name: $data['customer_name'],
            customer_phone: $data['customer_phone'] ?? null,
            total: $total,
            discount: $discount,
            tax: Constants::TAX,
            grand_total: $grandTotal,
            store_id: $data['store_id'],
            items: $data['items'],
        );

    }


    public function toArray()
    {

    }

    private static function getTotal(array $items)
    {
        return 0;
    }

    private static function getGrandTotal(float $total, float $discount)
    {
        $discount = $discount * $total;
        $tax = Constants::TAX * $total;
        return $total - $discount + $tax;
    }
}