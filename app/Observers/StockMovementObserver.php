<?php

namespace App\Observers;

use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\StockMovement;
use Illuminate\Support\Facades\Log;

class StockMovementObserver
{
    public function __construct(private StockMovementRepo $repo)
    {
    }
    /**
     * Handle the StockMovement "created" event.
     */
    public function created(StockMovement $stockMovement): void
    {
        Log::info("StockMovement Observer created", ['stockMovement' => $stockMovement]);
        $this->repo->updateAvailableStock($stockMovement);
    }
}
