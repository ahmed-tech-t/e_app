<?php

namespace App\Application\Services;

use App\Domain\Entities\SupplierEntity;
use App\Domain\Repo\SupplierRepo;

class SupplierService extends BaseService
{
    protected string $entityClass = SupplierEntity::class;

    public function __construct(SupplierRepo $repo)
    {
        $this->repo = $repo;
    }
}