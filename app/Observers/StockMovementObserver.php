<?php

namespace App\Observers;

use App\Application\Services\StockService;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\StockMovement;
use Illuminate\Support\Facades\Log;

class StockMovementObserver
{
    public function __construct(private StockService $stockService)
    {
    }
    /**
     * Handle the StockMovement "created" event.
     */
    public function created(StockMovement $stockMovement): void
    {
        Log::info("Iam Observer Stock movement created with ID {$stockMovement->id}");
        $this->stockService->updateInventory($stockMovement);
    }
}
