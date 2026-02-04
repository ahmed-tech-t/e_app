<?php

namespace App\Application\UseCases\Product;

use App\Domain\Repo\ProductRepo;

class DeleteProductUseCase
{
    public function __construct(private ProductRepo $repo)
    {
    }

    public function execute(int $id): string
    {
        return $this->repo->destory($id);
    }
}