<?php

namespace App\Application\DTOs;



class SalesReturnsDto
{

    public function __construct(
        public int $sales_id,
        public int $store_id,
        /** @var SalesReturnItemDto[] */
        public array $items,

    ) {
    }


    public function toArray()
    {
        return [
            'sales_id' => $this->sales_id,
            'store_id' => $this->store_id,
            'items' => $this->items
        ];
    }
}