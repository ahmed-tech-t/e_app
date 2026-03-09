<?php

namespace App\Application\Services;

use App\Domain\Entities\SalesItemsEntity;
use App\Domain\Repo\SalesItemsRepo;

class SalesItemsService extends BaseService
{
    protected string $entityClass = SalesItemsEntity::class;

    public function __construct(SalesItemsRepo $repo)
    {
        $this->repo = $repo;
    }
}