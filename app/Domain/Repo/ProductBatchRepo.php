<?php
namespace App\Domain\Repo;

use App\Domain\Entities\ProductBatchEntity;

interface ProductBatchRepo extends BaseRepo
{
    public function search($code);

    public function getProductBatchesInLocation($productId, $locationId);
    public function getProductQuantityInLocation($productId, $locationId);
}

