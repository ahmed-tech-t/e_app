<?php
namespace App\Domain\Repo;

interface PurchaseItemRepo extends BaseRepo
{
    public function getItemsQuantitiesInBill(int $purchaseId, array $productIds): array;
}

