<?php

namespace App\Application\DTOs;

class StockMovementTransferDto
{
    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Constructs a new StockMovementTransferDto object.
     *
     * @param int|null $fromLocationId The ID of the location to transfer from.
     * @param int|null $toLocationId The ID of the location to transfer to.
     * @param int|null $productId The ID of the product being transferred.
     * @param int|null $quantity The quantity of the product being transferred.
     */
    /*******  ff752598-b04d-4d5d-9c03-f1419da5e5ef  *******/
    public function __construct(
        public ?int $productId = null,
        public ?int $fromLocationId = null,
        public ?int $toLocationId = null,
        public ?int $quantity = null,
    ) {
    }


    public function toArray()
    {
        return [
            'from_location_id' => $this->fromLocationId,
            'to_location_id' => $this->toLocationId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
        ];
    }
}