<?php

namespace App\Application\Services;

use App\Domain\Entities\SaleUnitEntity;
use App\Domain\Repo\SaleUnitRepo;

class SaleUnitService extends BaseService
{
    protected string $entityClass = SaleUnitEntity::class;
    public function __construct(SaleUnitRepo $saleUnitRepo)
    {
        $this->repo = $saleUnitRepo;

    }
}