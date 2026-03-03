<?php
namespace App\Domain\Repo;

use App\Domain\Entities\ProductBatchEntity;

interface ProductBatchRepo extends BaseRepo
{
    public function getProductQuantityInLocation($productId, $locationId);
    public function getBatchesInLocation($productId, $locationId);
}

