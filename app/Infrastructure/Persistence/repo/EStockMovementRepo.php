<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\DTOs\StockMovementSearchDto;
use App\Application\Mapper\StockMovementMapper;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByBillNumber;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByLocationId;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByProductBatchId;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByProductId;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\FilterByType;
use App\Infrastructure\Persistence\Pipeline\Filters\StockMovement\StockMovementQueryContext;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EStockMovementRepo implements StockMovementRepo
{
    public function __construct()
    {
    }

    public function transfer($batchId, $fromLocationId, $toLocationId, $quantity, $billNumber = null)
    {
        self::create($batchId, $fromLocationId, -$quantity, StockMovementType::TRANSFER_OUT, $billNumber);
        self::create($batchId, $toLocationId, $quantity, StockMovementType::TRANSFER_IN, $billNumber);
    }

    public function adjust($batchId, $locationId, $quantity, $type, $billNumber = null)
    {
        self::create($batchId, $locationId, $quantity, $type, $billNumber);
    }


    public function updateAvailableStock(StockMovement $stockMovement)
    {
        Log::debug("EStockMovementRepo updateAvailableStock", ['stockMovement' => $stockMovement]);
        DB::transaction(function () use ($stockMovement) {

            $batch = ProductBatch::findOrFail($stockMovement->product_batch_id);

            if ($stockMovement->type == StockMovementType::ADJUST_INITIAL || $stockMovement->type == StockMovementType::ENTRY) {
                //  Log::info("EStockMovementRepo updateAvailableStock  Entry", ['stockMovement' => $stockMovement->type]);
                $batch->increment('initial_quantity', $stockMovement->quantity);
            }

            $batch->increment('remaining_quantity', $stockMovement->quantity);

            $exists = $batch->locations()
                ->where('location_id', $stockMovement->location_id)
                ->exists();

            if ($exists) {
                $batch->locations()
                    ->wherePivot('location_id', $stockMovement->location_id)
                    ->increment('remaining_quantity', $stockMovement->quantity);
            } else {
                $batch
                    ->locations()
                    ->attach(
                        $stockMovement->location_id,
                        ['remaining_quantity' => $stockMovement->quantity]
                    );
            }
        });
    }

    public function findAll()
    {
        return StockMovement::paginate()->through(
            fn($item) =>
            StockMovementMapper::toEntity($item)
        );
    }



    public function create($productBatchId, $locationId, $quantity, $type, $billNumber)
    {
        StockMovement::create([
            'product_batch_id' => $productBatchId,
            'location_id' => $locationId,
            'quantity' => $quantity,
            'type' => $type,
            'bill_number' => $billNumber
        ]);
    }

    public function isTransferOut($batchId)
    {
        return StockMovement::where('product_batch_id', $batchId)
            ->where('type', StockMovementType::TRANSFER_OUT)
            ->exists();
    }

    /**
     * @inheritDoc
     */
    public function search(StockMovementSearchDto $dto, $perPage = 5)
    {

        $context = StockMovementQueryContext::create(StockMovement::query(), $dto);

        $context = app(Pipeline::class)
            ->send($context)
            ->through([
                FilterByProductId::class,
                FilterByProductBatchId::class,
                FilterByLocationId::class,
                FilterByType::class,
                FilterByBillNumber::class,
            ])
            ->thenReturn();

        $result = $context->query
            ->with('batch')
            ->with('location')

            ->paginate($perPage)
            ->through(
                fn($item) => StockMovementMapper::toEntity($item)

            );

        return $result;

    }
}