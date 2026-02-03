<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\CategoryMapper;
use App\Domain\Repo\Product\CategoryRepo;
use App\Domain\Entities\CategoryEntity;
use App\Infrastructure\Persistence\Models\Category;

class ECategoryRepo implements CategoryRepo
{

    public function codeExists(string $code): bool
    {
        return Category::where('code', $code)->exists();
    }


    public function create(CategoryEntity $entity): CategoryEntity
    {
        $model = Category::create($entity->toArray())->refresh();
        return CategoryMapper::modelToEntity($model);
    }

    public function destory(int $id): string
    {
        //TODO handel product relations
        Category::destroy($id);
        return 'category deleted';
    }

    public function findAll()
    {
        $entities = Category::all()->map(fn($model) => CategoryMapper::modelToEntity($model));
        return $entities;
    }


    public function findById(int $id): CategoryEntity
    {
        $model = Category::findOrFail($id);
        return CategoryMapper::modelToEntity($model);
    }

    public function update(CategoryEntity $entity): CategoryEntity
    {
        $model = Category::findOrFail($entity->id);
        Category::where('id', $entity->id)->update($entity->toArray());
        return CategoryMapper::modelToEntity($model->refresh());
    }
}