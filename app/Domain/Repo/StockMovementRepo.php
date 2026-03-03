<?php

namespace App\Domain\Repo;

use App\Application\DTOs\StockMovementSearchDto;
use App\Infrastructure\Persistence\Models\StockMovement;

interface StockMovementRepo
{

    public function create($productBatchId, $locationId, $quantity, $type, $billNumber);
    public function findAll();
    public function updateAvailableStock(StockMovement $stockMovement);
    public function adjust($batchId, $locationId, $quantity, $type, $billNumber = null);
    public function isTransferOut($batchId);

    public function transfer($batchId, $fromLocationId, $toLocationId, $quantity);

    public function search(StockMovementSearchDto $dto);

}