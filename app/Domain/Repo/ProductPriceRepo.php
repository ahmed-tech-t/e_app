<?php
namespace App\Domain\Repo;

use App\Infrastructure\Persistence\Models\ProductPrice;

interface ProductPriceRepo extends BaseRepo
{
    public function createMany(array $entities): bool;
    public function invalidateOldPrice(ProductPrice $productPrice): bool;
}

