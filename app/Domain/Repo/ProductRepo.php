<?php

namespace App\Domain\Repo;

use App\Application\DTOs\ProductSearchDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepo extends BaseRepo
{
    public function search(ProductSearchDto $dto, int $perPage): LengthAwarePaginator;
}