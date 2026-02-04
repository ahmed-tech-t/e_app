<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\CategoryMapper;
use App\Domain\Repo\CategoryRepo;
use App\Domain\Entities\CategoryEntity;
use App\Infrastructure\Persistence\Models\Category;

class ECategoryRepo extends BaseERepo implements CategoryRepo
{
    protected $modelClass = Category::class;
    protected $mapper = CategoryMapper::class;

}