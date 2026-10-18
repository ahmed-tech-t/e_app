<?php

namespace App\Application\Services;

use App\Application\DTOs\UpdateProductPriceDto;
use App\Domain\Entities\ProductPriceEntity;
use App\Domain\Repo\ProductPriceRepo;
use App\Infrastructure\Persistence\utils\PriceType;

class ProductPriceService extends BaseService
{
    protected string $entityClass = ProductPriceEntity::class;

    public function __construct(ProductPriceRepo $repo)
    {
        $this->repo = $repo;
    }

    public function setNewPrice(UpdateProductPriceDto $dto)
    {
        $entity = ProductPriceEntity::create($dto->toArray());
        $this->repo->create($entity);
    }

    public function getProductPriceHistory(int $productId, ?PriceType $type)
    {

        return $this->repo->getProductPriceHistory($productId, $type);
    }
}
