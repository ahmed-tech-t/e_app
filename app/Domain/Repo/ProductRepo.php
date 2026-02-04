<?php

namespace App\Domain\Repo;

use App\Application\DTOs\ProductSearchDto;
use App\Domain\Entities\ProductEntity;
use App\Domain\Entities\ProductSearchEntity;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepo extends BaseRepo
{
    public function search(ProductSearchDto $dto, int $perPage): LengthAwarePaginator;
}