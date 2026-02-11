<?php
namespace App\Domain\Repo;

use App\Domain\Entities\ProductBatchEntity;

interface ProductBatchRepo extends BaseRepo
{
    public function createBatchAndSetLocation(ProductBatchEntity $entity, int $locationId);
    public function search($code);

    public function update($entity, ?int $initialQuantity = null);
    public function getProductBatchesInLocation($productId, $locationId);
    public function getProductQuantityInLocation($productId, $locationId);

}

