<?php

namespace App\Domain\Repo;

interface SalesReturnItemRepo extends BaseRepo
{
    public function getReturnedItemQuantityInBill(int $saleId, int $productId): int;

    public function getReturnedItemsQuantitiesInBill(int $saleId, array $productIds): array;
}
