<?php
namespace App\Domain\Repo;

interface SalesItemRepo extends BaseRepo
{
    public function getItemQuantityInBill(int $saleId, int $productId): int;
    public function getItemsQuantitiesInBill(int $saleId, array $productIds): array;
}

