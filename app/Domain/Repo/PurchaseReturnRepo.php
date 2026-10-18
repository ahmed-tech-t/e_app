<?php

namespace App\Domain\Repo;

interface PurchaseReturnRepo extends BaseRepo
{
    public function findByOriginalBillId(int $id);

    public function isThisFirstReturn(int $purchaseId): bool;
}
