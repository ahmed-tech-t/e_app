<?php

namespace App\Application\Services;

use App\Application\DTOs\StockMovementSearchDto;
use App\Application\Mapper\ProductBatchMapper;
use App\Domain\Entities\ProductBatchEntity;
use App\Domain\Repo\ProductBatchRepo;
use App\Domain\Repo\ProductRepo;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    public function __construct(
        private StockMovementRepo $stockMovementRepo,
        private ProductBatchRepo $productBatchRepo,
        private ProductRepo $productRepo
    ) {
    }

    public function search(StockMovementSearchDto $dto)
    {
        return $this->stockMovementRepo->search($dto);
    }


    public function createProductBatch(ProductBatchEntity $entity, int $locationId)
    {
        return DB::transaction(function () use ($entity, $locationId) {
            $model = ProductBatch::create($entity->toArray());
            $this->stockMovementRepo->adjust(batchId: $model->id, locationId: $locationId, quantity: $entity->initialQuantity, type: StockMovementType::ENTRY);
            $model->refresh();
            return ProductBatchMapper::toEntity($model);

        });
    }
    public function transferProduct($dto)
    {
        DB::transaction(
            function () use ($dto) {

                $batches = $this->productBatchRepo->getBatchesInLocation($dto->productId, $dto->fromLocationId);

                $this->isQuantityAvailable($batches, $dto->quantity, $dto->productId, $dto->fromLocationId);

                $this->handelBatchesMovement(
                    batches: $batches,
                    quantity: $dto->quantity,
                    callBack: function ($batch, $canTake) use ($dto) {
                        $this->stockMovementRepo->transfer(
                            $batch->batch_id,
                            $dto->fromLocationId,
                            $dto->toLocationId,
                            $canTake
                        );
                    }
                );

                return true;
            }
        );
    }


    public function sale($productId, $locationId, $quantity, $billNumber)
    {

        DB::transaction(
            function () use ($billNumber, $productId, $locationId, $quantity) {

                $batches = $this->productBatchRepo->getBatchesInLocation($productId, $locationId);
                $this->isQuantityAvailable($batches, $quantity, $productId, $locationId);
                $this->handelBatchesMovement(
                    batches: $batches,
                    quantity: $quantity,
                    callBack: function ($batch, $canTake) use ($billNumber, $locationId) {
                        $this->stockMovementRepo->adjust(
                            $batch->batch_id,
                            $locationId,
                            -$canTake,
                            StockMovementType::SALE,
                            $billNumber
                        );
                    }
                );

                return true;
            }
        );
    }
    // i think this is not needed
    private function updateBatch(ProductBatchEntity $entity, ?int $initialQuantity = null)
    {
        return DB::transaction(function () use ($entity, $initialQuantity) {

            $model = ProductBatch::
                lockForUpdate()
                ->findOrFail($entity->id);

            if (
                $initialQuantity
                && $model->remaining_quantity == $model->initial_quantity
            ) {
                if (!$this->stockMovementRepo->isTransferOut($model->id)) {
                    $diff = (float) $initialQuantity - (float) $model->initial_quantity;
                    $locationId = $model->locations()->first()->id;
                    $this->stockMovementRepo->adjust(
                        batchId: $entity->id,
                        locationId: $locationId,
                        quantity: $diff,
                        type: StockMovementType::ADJUST_INITIAL
                    );
                } else
                    throw new \Exception("Cannot update initial quantity after stock movement");
            }

            $model->update($entity->toArray());
            return ProductBatchMapper::toEntity($model->refresh());
        });
    }

    private function isQuantityAvailable($batches, $quantity, $productId, $currentLocationId): bool
    {
        $inStock = $batches->sum('quantity');
        if (!$inStock || $inStock <= 0 || $inStock < $quantity) {
            $product = $this->productRepo->findById($productId);
            $availableInOtherLocations = $this->productBatchRepo->isAvailableInOtherLocation($productId, $currentLocationId);

            $isOutOfStock = (!$inStock || $inStock <= 0);
            $statusMessage = $isOutOfStock
                ? "Product '{$product->name_ar}' is out of stock"
                : "Product '{$product->name_ar}' has insufficient quantity, available quantity is $inStock";

            if ($availableInOtherLocations) {
                $statusMessage .= ", but available in other locations";
            }

            throw new \Exception($statusMessage);
        }

        return true;
    }

    private function handelBatchesMovement($batches, $quantity, callable $callBack)
    {
        $remainingToTake = $quantity;
        foreach ($batches as $batch) {
            $canTake = min($batch->quantity, $remainingToTake);
            $callBack($batch, $canTake);
            $remainingToTake -= $canTake;
            if ($remainingToTake <= 0)
                break;
        }
    }
}