<?php

namespace App\Domain\Repo\Product;

use App\Domain\Entities\ProductEntity;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepo
{
    public function getPaginatedProducts($perPage): LengthAwarePaginator;
    public function findById(int $id): ProductEntity;
    public function codeExists(string $code): bool;

    public function create(ProductEntity $data): ProductEntity;
    public function update(ProductEntity $product): ProductEntity;
    public function destory(int $id): string;
}