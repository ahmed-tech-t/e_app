<?php

namespace App\Application\Services;

use App\Domain\Entities\PurchaseItemEntity;
use App\Domain\Repo\PurchaseItemRepo;

class PurchaseItemService extends BaseService
{
    protected string $entityClass = PurchaseItemEntity::class;

    public function __construct(PurchaseItemRepo $repo)
    {
        $this->repo = $repo;
    }
}