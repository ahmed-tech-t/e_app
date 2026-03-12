<?php
namespace App\Domain\Repo;

use App\Infrastructure\Persistence\Models\ProductBatch;

interface ProductBatchRepo extends BaseRepo
{
    public function getProductQuantityInLocation($productId, $locationId);
    public function getBatchesInLocation($productId, $locationId);

    public function isAvailableInOtherLocation($productId, $locationId);

    public function attachToLocation(int $batchId, int $locationId, float $quantity);
    public function addToLocation(ProductBatch $batchModel, int $locationId, float $quantity);

    public function updateStock($entity, $quantity, $type, $locationId);

}

