<?php

namespace App\Application\Services;

use App\Domain\Entities\SalesEntity;
use App\Domain\Repo\SalesRepo;

class SalesService extends BaseService
{
    protected string $entityClass = SalesEntity::class;

    public function __construct(SalesRepo $repo)
    {
        $this->repo = $repo;
    }
}