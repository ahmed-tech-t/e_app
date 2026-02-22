<?php

namespace App\Observers;

use App\Domain\Repo\ProductPriceRepo;
use App\Infrastructure\Persistence\Models\ProductPrice;

class ProductPriceObserver
{
    public function __construct(private ProductPriceRepo $repo)
    {

    }

    public function creating(ProductPrice $productPrice): bool
    {
        return $this->repo->invalidateOldPrice($productPrice);
    }
}
