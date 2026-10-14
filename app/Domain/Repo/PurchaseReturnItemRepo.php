<?php

namespace App\Domain\Repo;

interface PurchaseReturnItemRepo extends BaseRepo
{
    public function getReturnedItemsQuantitiesInBill(int $purchaseId, array $productIds): array;
}
