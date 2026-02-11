<?php

namespace App\Domain\Repo;

use App\Infrastructure\Persistence\Models\StockMovement;

interface StockMovementRepo
{

    public function create($productBatchId, $locationId, $quantity, $type);
    public function findAll();
    public function updateAvilableStock(StockMovement $stockMovement);
    public function transfer($batchId, $fromLocationId, $toLocationId, $quantity);
    public function adjust($batchId, $locationId, $quantity);
    public function entry($batchId, $locationId, $quantity);
    public function sale($batchId, $locationId, $quantity);
}