<?php
namespace App\Domain\Repo;

interface SalesReturnsRepo extends BaseRepo
{
    public function findByOriginalBillId(int $id);
    public function isThisFirstReturn(int $salesId): bool;
}

