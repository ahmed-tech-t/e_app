<?php

namespace App\Application\UseCases\Product;

use App\Application\DTOs\CreateProductDto;
use App\Application\Mapper\ProductMapper;
use App\Domain\Entities\ProductEntity;
use App\Domain\Repo\Product\CategoryRepo;
use App\Domain\Repo\Product\ProductRepo;
use App\Traits\CodeGenerator;

class CreateProductUseCase
{
    use CodeGenerator;

    public function __construct(private ProductRepo $productRepo, private CategoryRepo $categoryRepo)
    {
    } // dependency injection
    public function execute(CreateProductDto $dto): ProductEntity
    {
        $entity = ProductMapper::createProductDtoToEntity($dto);
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