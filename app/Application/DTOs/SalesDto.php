<?php

namespace App\Application\DTOs;



class SalesDto
{

    public function __construct(
        public string $customer_name,
        public array $items,
        public int $store_id,
        public ?float $discount = null,
        public ?string $customer_phone = null,
    ) {

    }


    public function toArray()
    {
        return [
            'customer_name' => $this->customer_name,
            'items' => $this->items,
            'store_id' => $this->store_id,
            'discount' => $this->discount,
            'customer_phone' => $this->customer_phone
        ];
    }
}