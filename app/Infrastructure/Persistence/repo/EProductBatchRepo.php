<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\ProductBatchMapper;
use App\Domain\Repo\ProductBatchRepo;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch\FilterByCode;
use App\Infrastructure\Persistence\Pipeline\Filters\ProductBatch\ProductBatchQueryContext;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EProductBatchRepo extends BaseERepo implements ProductBatchRepo
{
    protected $modelClass = ProductBatch::class;

    protected $mapper = ProductBatchMapper::class;

    protected $queryContext = ProductBatchQueryContext::class;

    protected array $withSearch = ['locations', 'product'];

    protected array $searchFilters = [FilterByCode::class];

    public function __construct() {}

    public function getBatchByProductIdFIFO($productId)
    {
        return ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0)
            ->oldest()->first();
    }

    public function getProductQuantityInLocation($productId, $locationId)
    {
        return ProductBatch::withLocationStock($locationId, $productId)
            ->sum('batch_locations.remaining_quantity');
    }

    public function getProductBatchesInLocation($productId, $locationId)
    {
        return ProductBatch::withLocationStock($locationId, $productId)
            ->orderBy('product_batches.created_at', 'asc') // FIFO
            ->lockForUpdate()
            ->get();
    }

    public function getBatchesInLocation($productId, $locationId)
    {
        return ProductBatch::withLocationStock($locationId, $productId)
            ->orderBy('product_batches.created_at', 'asc')
            ->select([
                'product_batches.id as batch_id',
                'batch_locations.location_id as location_id',
                'batch_locations.remaining_quantity as quantity',
            ])
            ->lockForUpdate()
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function isAvailableInOtherLocation($productId, $locationId)
    {
        return ProductBatch::where('product_id', $productId)
            ->where('remaining_quantity', '>', 0)
            ->whereHas('locations', function ($query) use ($locationId) {
                $query->where('location_id', '!=', $locationId);
            })
            ->exists();
    }

    public function attachToLocation(int $batchId, int $locationId, float $quantity): void
    {
        $batchModel = ProductBatch::findOrFail($batchId);
        $this->addToLocation($batchModel, $locationId, $quantity);
    }

    public function addToLocation(ProductBatch $batchModel, int $locationId, float $quantity)
    {
        Log::info("Attaching batch {$batchModel->id} to location {$locationId} with quantity {$quantity}");
        $existing = DB::table('batch_locations')
            ->where('product_batch_id', $batchModel->id)
            ->where('location_id', $locationId)
            ->first();

        if ($existing) {
            DB::table('batch_locations')
                ->where('product_batch_id', $batchModel->id)
                ->where('location_id', $locationId)
                ->update([
                    'remaining_quantity' => $existing->remaining_quantity + $quantity,
                    'updated_at' => now(),
                ]);
        } else {
            $batchModel->locations()->attach($locationId, [
                'remaining_quantity' => $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function updateStock($entity, $quantity, $type, $locationId)
    {

        Log::info("Updating stock for batch {$entity->id} with quantity {$quantity} and type {$type->name} at location ID {$locationId}");
        $model = ProductBatch::findOrFail($entity->id);

        $updateData = [
            'remaining_quantity' => $entity->remaining_quantity,
        ];
        if ($type == StockMovementType::ENTRY || $type == StockMovementType::ADJUST_INITIAL) {
            $updateData['initial_quantity'] = $entity->initial_quantity;
        }

        $model->update($updateData);

        $this->addToLocation($model, $locationId, $quantity);
    }
}
