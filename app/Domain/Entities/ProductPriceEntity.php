<?php

namespace App\Domain\Entities;

use App\Infrastructure\Persistence\utils\PriceType;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class ProductPriceEntity
{

    public function __construct(
        public int $productId,
        public float $price,
        public PriceType $type,
        public Carbon $validFrom,
        public ?int $id = null,
        public ?Carbon $validTo = null,
    ) {
    }

    public static function create(array $data)
    {
        return new self(
            productId: $data['product_id'],
            type: $data['type'],
            price: $data['price'],
            validFrom: Carbon::now(),
        );
    }

    public function invalidate()
    {
        $this->validTo = Carbon::now();
    }
    public function toArray()
    {
        return [
            'product_id' => $this->productId,
            'type' => $this->type,
            'price' => $this->price,
            'valid_from' => $this->validFrom,
            'valid_to' => $this->validTo,
        ];
    }
}