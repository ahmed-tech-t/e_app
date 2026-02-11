<?php

namespace App\Application\Services;

use App\Domain\Entities\ProductBatchEntity;
use App\Domain\Repo\ProductBatchRepo;
use Illuminate\Support\Facades\Log;

class ProductBatchService extends BaseService
{
    protected string $entityClass = ProductBatchEntity::class;

    public function __construct(ProductBatchRepo $repo)
    {
        $this->repo = $repo;
    }

    public function create($dto)
    {
        $entity = ProductBatchEntity::create($dto->toArray());
        return ($this->repo)->createBatchAndSetLocation($entity, $dto->locationId);
    }

    public function update($dto, int $id)
    {
        $entity = $this->repo->findById($id);
        $entity = $entity->update($dto->toArray());
        //  Log::info("Your message here", ['dto' => $dto]);
        return ($this->repo)->update($entity, $dto->initialQuantity);
    }

    public function search($code)
    {
        return ($this->repo)->search($code);
    }
}