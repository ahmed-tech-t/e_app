<?php
namespace App\Domain\Repo;

use App\Infrastructure\Persistence\Models\ProductPrice;
use App\Infrastructure\Persistence\utils\PriceType;

interface ProductPriceRepo extends BaseRepo
{
    public function createMany(array $entities): bool;
    public function invalidateOldPrice(ProductPrice $productPrice): bool;

    public function getProductPriceHistory(int $productId, ?PriceType $type);
    public function getByProductIdAndType(int $productId, PriceType $type);
}

