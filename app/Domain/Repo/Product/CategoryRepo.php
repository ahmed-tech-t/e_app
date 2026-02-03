<?php

namespace App\Domain\Repo\Product;

use App\Domain\Entities\CategoryEntity;

interface CategoryRepo
{
    public function findAll();
    public function findById(int $id): CategoryEntity;
    public function codeExists(string $code): bool;

    public function create(CategoryEntity $entity): CategoryEntity;
    public function update(CategoryEntity $entity): CategoryEntity;
    public function destory(int $id): string;
}