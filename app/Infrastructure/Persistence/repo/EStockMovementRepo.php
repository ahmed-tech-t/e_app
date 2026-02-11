<?php

namespace App\Infrastructure\Persistence\repo;

use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;

class EStockMovementRepo implements StockMovementRepo
{

    public function transfer($batchId, $fromLocationId, $toLocationId, $quantity)
    {
        self::create($batchId, $fromLocationId, -$quantity, StockMovementType::TRANSFER_OUT);
        self::create($batchId, $toLocationId, $quantity, StockMovementType::TRANSFER_IN);
    }

    public function adjust($batchId, $locationId, $quantity)
    {
        self::create($batchId, $locationId, $quantity, StockMovementType::ADJUSTMENT);
    }

    public function entry($batchId, $locationId, $quantity)
    {
        self::create($batchId, $locationId, $quantity, StockMovementType::ENTRY);
    }

    public function sale($batchId, $locationId, $quantity)
    {
        self::create($batchId, $locationId, -$quantity, StockMovementType::SALE);
    }

    public function updateAvilableStock(StockMovement $stockMovement)
    {
        DB::transaction(function () use ($stockMovement) {

            $batch = ProductBatch::findOrFail($stockMovement->product_batch_id);

            if ($stockMovement->type == StockMovementType::ADJUSTMENT || $stockMovement->type == StockMovementType::ENTRY) {
                $batch->increment('initial_quantity', $stockMovement->quantity);
            }

            $batch->increment('remaining_quantity', $stockMovement->quantity);

            $batch->locations()->syncWithoutDetaching([$stockMovement->location_id]);

            // تحديث الكمية في الـ Pivot مباشرة باستخدام increment لضمان الدقة
            // ملاحظة: الـ increment على الـ Pivot محتاجة query مباشر
            DB::table('batch_locations') // اسم جدول الـ pivot بتاعك
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
}