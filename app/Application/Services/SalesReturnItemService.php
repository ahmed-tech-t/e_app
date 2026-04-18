<?php

namespace App\Application\Services;

use App\Domain\Entities\SalesReturnItemEntity;
use App\Domain\Repo\SalesReturnItemRepo;

class SalesReturnItemService extends BaseService
{
    protected string $entityClass = SalesReturnItemEntity::class;

    public function __construct(SalesReturnItemRepo $repo)
    {
        $this->repo = $repo;
    }
}