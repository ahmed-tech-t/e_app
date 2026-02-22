<?php

namespace App\Application\UseCases\Product;

use App\Application\DTOs\CreateProductDto;
use App\Domain\Entities\ProductEntity;
use App\Domain\Entities\ProductPriceEntity;
use App\Domain\Repo\CategoryRepo;
use App\Domain\Repo\ProductPriceRepo;
use App\Domain\Repo\ProductRepo;
use App\Infrastructure\Persistence\Models\ProductPrice;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Traits\CodeGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateProductUseCase
{
    use CodeGenerator;

    public function __construct(
        private ProductRepo $productRepo,
        private ProductPriceRepo $productPriceRepo,
        private CategoryRepo $categoryRepo
    ) {
    }
    public function execute(CreateProductDto $dto): ProductEntity
    {
        return DB::transaction(function () use ($dto) {

            $entity = ProductEntity::create($dto->toArray());

            $entity->code = $this->getCode($entity->category_id);

            $product = $this->productRepo->create($entity);

            $this->createProductPrice(
                productId: $product->id,
                retailPrice: $entity->retail_price,
                wholesalePrice: $entity->wholesale_price
            );


            $product->retail_price = $dto->retail_price;
            $product->wholesale_price = $dto->wholesale_price;

            return $product;
        });

    }

    private function createProductPrice($productId, $retailPrice, $wholesalePrice)
    {
        $this->productPriceRepo->createMany(
            [
                ProductPriceEntity::create([
                    'product_id' => $productId,
                    'type' => PriceType::RETAIL,
                    'price' => $retailPrice
                ]),
                ProductPriceEntity::create([
                    'product_id' => $productId,
                    'type' => PriceType::WHOLESALE,
                    'price' => $wholesalePrice
                ]),
            ]
        );
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