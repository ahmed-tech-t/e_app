<?php

namespace App\Application\UseCases\Product;

use App\Application\DTOs\CreateProductDto;
use App\Domain\Entities\ProductEntity;
use App\Domain\Repo\CategoryRepo;
use App\Domain\Repo\ProductRepo;
use App\Traits\CodeGenerator;

class CreateProductUseCase
{
    use CodeGenerator;

    public function __construct(private ProductRepo $productRepo, private CategoryRepo $categoryRepo)
    {
    } // dependency injection
    public function execute(CreateProductDto $dto): ProductEntity
    {
        $entity = ProductEntity::create($dto->toArray());
        $entity->code = $this->getCode($entity->categoryId);
        return $this->productRepo->create($entity);
    }

    private function getCode(int $categoryId): string
    {
        $category = $this->categoryRepo->findById($categoryId);

        do {
            $code = $this->generateCode($category->name_en);
        } while ($this->productRepo->codeExists($code));

        return $code;
    }
}