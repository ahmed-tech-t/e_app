<?php

namespace App\Infrastructure\Persistence\repo;

use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EStockMovementRepo implements StockMovementRepo
{

    public function transfer($batchId, $fromLocationId, $toLocationId, $quantity)
    {
        self::create($batchId, $fromLocationId, -$quantity, StockMovementType::TRANSFER_OUT);
        self::create($batchId, $toLocationId, $quantity, StockMovementType::TRANSFER_IN);
    }

    public function adjust($batchId, $locationId, $quantity, $type)
    {
        self::create($batchId, $locationId, $quantity, $type);
    }


    public function updateAvailableStock(StockMovement $stockMovement)
    {
        DB::transaction(function () use ($stockMovement) {

            $batch = ProductBatch::findOrFail($stockMovement->product_batch_id);

            if ($stockMovement->type == StockMovementType::ADJUST_INITIAL || $stockMovement->type == StockMovementType::ENTRY) {
                //  Log::info("EStockMovementRepo updateAvailableStock  Entry", ['stockMovement' => $stockMovement->type]);
                $batch->increment('initial_quantity', $stockMovement->quantity);
            }

            $batch->increment('remaining_quantity', $stockMovement->quantity);
            //   Log::info("EStockMovementRepo updateAvailableStock ", ['productBatch' => $batch]);

            $batch->locations()->syncWithoutDetaching([$stockMovement->location_id]);

            DB::table('batch_locations')
                ->where('product_batch_id', $stockMovement->product_batch_id)
                ->where('location_id', $stockMovement->location_id)
                ->increment('remaining_quantity', $stockMovement->quantity);

        });
    }

    public function findAll()
    {
        return StockMovement::all();
    }


    /**
     * @inheritDoc
     */
    public function create($productBatchId, $locationId, $quantity, $type)
    {
        StockMovement::create([
            'product_batch_id' => $productBatchId,
            'location_id' => $locationId,
            'quantity' => $quantity,
            'type' => $type
        ]);
    }

    public function isTransferOut($batchId)
    {
        return StockMovement::where('product_batch_id', $batchId)
            ->where('type', StockMovementType::TRANSFER_OUT)
            ->exists();
    }


}