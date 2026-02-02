<?php
namespace App\Application\UseCases\Product;

use App\Domain\Entities\ProductEntity;
use App\Domain\Repo\Product\ProductRepo;

class GetProductByIdUseCase
{
    public function __construct(private ProductRepo $repo)
    {
    }

    public function execute(int $id): ProductEntity
    {
        return $this->repo->findById($id);
    }
}
