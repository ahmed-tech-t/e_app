<?php

namespace App\Application\Services;

use App\Application\DTOs\UpdateProductPriceDto;
use App\Application\DTOs\UpdateProductPriceDtoDto;
use App\Domain\Entities\ProductPriceEntity;
use App\Domain\Repo\ProductPriceRepo;

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

}