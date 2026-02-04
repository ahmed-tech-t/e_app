<?php
namespace App\Application\UseCases\Product;

use App\Application\DTOs\UpdateProductDto;
use App\Domain\Repo\ProductRepo;



class UpdateProductUseCase
{
    public function __construct(private ProductRepo $repo)
    {
    }
    public function execute(UpdateProductDto $dto, int $id)
    {
        $product = $this->repo->findById($id);
        $product->update($dto->toArray());
        return $this->repo->update($product);
    }
}