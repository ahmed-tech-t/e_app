<?php

namespace App\Application\Services;

use App\Domain\Entities\ProductEntity;
use App\Domain\Entities\ProductPriceEntity;
use App\Domain\Repo\ProductPriceRepo;
use App\Domain\Repo\ProductRepo;
use App\Infrastructure\Persistence\utils\PriceType;

class ProductService extends BaseService
{
    protected string $entityClass = ProductEntity::class;

    public function __construct(ProductRepo $repo, private ProductPriceRepo $productPriceRepo)
    {
        $this->repo = $repo;
    }

    public function create($dto)
    {
        $product = parent::create($dto);

        $product->retail_price = $dto->retail_price;
        $product->wholesale_price = $dto->wholesale_price;

        $this->productPriceRepo->createMany(
            [
                ProductPriceEntity::create([
                    'product_id' => $product->id,
                    'type' => PriceType::RETAIL,
                    'price' => $product->retail_price
                ]),
                ProductPriceEntity::create([
                    'product_id' => $product->id,
                    'type' => PriceType::WHOLESALE,
                    'price' => $product->wholesale_price
                ]),
            ]
        );

        return $product;

    }

    public function findAllByLocation(int $locationId, int $perPage)
    {
        return $this->repo->findAllByLocation($locationId, $perPage);
    }

    public function findByLocation(int $productId, int $locationId)
    {
        return $this->repo->findByLocation($productId, $locationId);
    }
}