<?php

namespace App\Application\Services;

use App\Domain\Entities\ProductBatchEntity;
use App\Domain\Entities\PurchaseEntity;
use App\Domain\Entities\PurchaseItemEntity;
use App\Domain\Repo\ProductBatchRepo;
use App\Domain\Repo\ProductPriceRepo;
use App\Domain\Repo\PurchaseItemRepo;
use App\Domain\Repo\PurchaseRepo;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\utils\Constants;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseService extends BaseService
{
    protected string $entityClass = PurchaseEntity::class;

    public function __construct(
        PurchaseRepo $repo,
        private ProductPriceRepo $productPriceRepo,
        private ProductBatchRepo $productBatchRepo,
        private PurchaseItemRepo $purchaseItemRepo,
        private StockMovementRepo $stockMovementRepo

    ) {
        $this->repo = $repo;
    }


    public function preCreate($dto)
    {
        Log::info("Pre-creating purchase with supplier ID {$dto->supplier_id} at store ID {$dto->store_id} with tax {$dto->tax} and discount {$dto->discount}");
        $items = collect($dto->items)->map(function ($itemDto) {

            $this->checkPriceIntegrity($itemDto);

            return PurchaseItemEntity::create($itemDto);
        });

        return PurchaseEntity::create($dto->toArray(), $items->all());
    }


    public function create($dto)
    {
        $entity = $this->preCreate($dto);

        Log::info("Creating purchase with supplier ID {$dto->supplier_id} at store ID {$dto->store_id} with tax {$dto->tax} and discount {$dto->discount}");
        $entity->code = $this->getCode("PUR");

        return DB::transaction(function () use ($entity) {
            Log::info("Saving purchase to database with code {$entity->code}");
            $purchase = $this->repo->create($entity);

            $items = collect($entity->items)->map(function ($item) use ($purchase, $entity) {

                $item->purchase_id = $purchase->id;
                Log::info("Saving purchase item for product ID {$item->product_id} with quantity {$item->quantity} and price {$item->price}");
                $this->purchaseItemRepo->create($item);

                $createdBatch = $this->createProductBatch($purchase, $item, $entity->store_id);

                Log::info("Creating stock movement for created batch with ID {$createdBatch->id} at store ID {$entity->store_id}");
                $this->stockMovementRepo->create(
                    $createdBatch->id,
                    $entity->store_id,
                    $item->quantity,
                    StockMovementType::ENTRY,
                    $purchase->code
                );

                return $item;
            });
            $purchase->items = $items->all();

            return $purchase;
        });
    }


    private function createProductBatch($purchase, $item, $storeId)
    {
        Log::info("Creating product batch for product ID {$item->product_id} with initial quantity {$item->quantity} at store ID {$storeId}");
        $batch = ProductBatchEntity::create([
            "batch_code" => $purchase->code,
            "product_id" => $item->product_id,
            "initial_quantity" => $item->quantity,
            "cost_price" => $item->price,
        ]);

        $createdBatch = $this->productBatchRepo->create($batch);
        return $createdBatch;
    }

    private function checkPriceIntegrity($itemDto): void
    {
        $savedPrice = $this->productPriceRepo->getByProductIdAndType(
            $itemDto["product_id"],
            PriceType::WHOLESALE
        )->price;

        $priceWithMinProfit = $itemDto['price'] * (1 + Constants::MIN_PROFIT);
        if ($savedPrice < $priceWithMinProfit) {
            Log::info("price must be updated", ['data' => $itemDto]);
            // TODO send notification price must be updated
            //  event(new ProductPriceWarningEvent($itemDto["product_id"], $itemDto["price"]));
        }
    }


}