<?php

namespace App\Application\Services;

use App\Application\DTOs\StockMovementTransferDto;
use App\Domain\Repo\StockMovementRepo;

class StockMovementService
{


    public function __construct(private StockMovementRepo $repo)
    {
    }

    public function index()
    {
        return $this->repo->findAll();
    }

    public function transfer(StockMovementTransferDto $dto)
    {
        return $this->repo->transfer(
            batchId: $dto->batchId,
            fromLocationId: $dto->fromLocationId,
            toLocationId: $dto->toLocationId,
            quantity: $dto->quantity
        );
    }
}