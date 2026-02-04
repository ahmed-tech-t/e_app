<?php

namespace App\Application\Services;

use App\Application\DTOs\SaleUnitDto;
use App\Domain\Entities\SaleUnitEntity;
use App\Domain\Repo\SaleUnitRepo;
use App\Traits\CodeGenerator;

class SaleUnitService extends BaseService
{
    protected string $entityClass = SaleUnitEntity::class;
    protected string $repo = SaleUnitRepo::class;

}