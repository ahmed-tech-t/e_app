<?php

namespace App\Application\Services;

use App\Domain\Entities\ProductBatchEntity;
use App\Domain\Repo\ProductBatchRepo;

class ProductBatchService extends BaseService
{
    protected string $entityClass = ProductBatchEntity::class;

    public function __construct(ProductBatchRepo $repo)
    {
        $this->repo = $repo;
    }

    public function create($dto)
    {
        $entity = ($this->entityClass)::create($dto->toArray());
        return ($this->repo)->createBatchAndSetLocation($entity, $dto->locationId);
    }

    public function search($code)
    {
        return ($this->repo)->search($code);
    }
}