<?php

namespace App\Application\Services;

use App\Domain\Entities\CategoryEntity;
use App\Domain\Repo\Product\SaleUnitRepo;

class CategoryService extends BaseService
{
    protected string $entityClass = CategoryEntity::class;
    protected string $repo = CategoryEntity::class;
}