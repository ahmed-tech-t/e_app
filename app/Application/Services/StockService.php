<?php

namespace App\Application\Services;

use App\Application\Mapper\ProductBatchMapper;
use App\Domain\Entities\ProductBatchEntity;
use App\Domain\Repo\ProductBatchRepo;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(
        private StockMovementRepo $stockMovementRepo,
        private ProductBatchRepo $productBatchRepo
    ) {
    }

    public function createProductBatch(ProductBatchEntity $entity, int $locationId)
    {
        return DB::transaction(function () use ($entity, $locationId) {
            $model = ProductBatch::create($entity->toArray());
            $this->stockMovementRepo->adjust(batchId: $model->id, locationId: $locationId, quantity: $entity->initialQuantity, type: StockMovementType::ENTRY);
            $model->refresh();
            return ProductBatchMapper::modelToEntity($model);

        });
    }
    public function transferProduct($dto)
    {
        DB::transaction(
            function () use ($dto) {
                $this->$this->isQuantityAvailable($dto->productId, $dto->fromLocationId, $dto->quantity);

                $this->$this
                    ->getTargetBatchByLocation(
                        $dto->productId,
                        $dto->fromLocationId,
                        $dto->quantity,
                        function ($productBatchId, $fromLocationId, $quantity) use ($dto) {
                            $this->stockMovementRepo->transfer($productBatchId, $fromLocationId, $dto->toLocationId, $quantity);
                        }
                    );
                return true;
            }
        );
    }

    public function stockMovementHistory()
    {
        return $this->stockMovementRepo->findAll();
    }
    public function sale($productId, $locationId, $quantity, $billNumber)
    {

        DB::transaction(
            function () use ($billNumber, $productId, $locationId, $quantity) {

                $this->isQuantityAvailable(
                    $productId,
                    $locationId,
                    $quantity
                );
                $this->getTargetBatchByLocation(
                    $productId,
                    $locationId,
                    $quantity,
                    function ($productBatchId, $fromLocationId, $quantity) use ($billNumber) {
                        $this->stockMovementRepo->adjust(
                            $productBatchId,
                            $fromLocationId,
                            -$quantity,
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
            return ProductBatchMapper::modelToEntity($model->refresh());
        });
    }


    private function getTargetBatchByLocation($productId, $locationId, $quantity, callable $callback)
    {
        $productBatches = $this->productBatchRepo->getProductBatchesInLocation($productId, $locationId);

        foreach ($productBatches as $productBatch) {
            $canTake = min($productBatch->remaining_quantity, $quantity);
            $callback($productBatch->id, $locationId, $canTake);
            $quantity -= $canTake;
            if ($quantity <= 0)
                break;
        }
    }

    private function isQuantityAvailable($productId, $locationId, $quantity): bool
    {
        $totalQuantity = $this->productBatchRepo->getProductQuantityInLocation($productId, $locationId);
        if ($quantity > $totalQuantity) {
            throw new \Exception("we don't have enough quantity");
        }
        return true;
    }


}