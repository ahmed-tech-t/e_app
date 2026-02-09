<?php

namespace App\Application\Services;

use App\Domain\Entities\CategoryEntity;
use App\Domain\Repo\CategoryRepo;

class CategoryService extends BaseService
{
    protected string $entityClass = CategoryEntity::class;

    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->repo = $categoryRepo;
    }
}