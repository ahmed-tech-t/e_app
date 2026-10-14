<?php

namespace App\Application\Services;

use App\Application\DTOs\PurchaseReturnDto;
use App\Application\Mapper\PurchaseReturnMapper;
use App\Domain\Entities\PurchaseReturnEntity;
use App\Domain\Repo\PurchaseReturnItemRepo;
use App\Domain\Repo\PurchaseReturnRepo;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseReturnService extends BaseService
{
    protected string $entityClass = PurchaseReturnEntity::class;

    public function __construct(
        PurchaseReturnRepo $repo,
        private PurchaseReturnItemRepo $purchaseReturnItemRepo,
        private PurchaseService $purchaseService,
        private StockService $stockService,
        private StockMovementRepo $stockMovementRepo,
    ) {
        $this->repo = $repo;
    }

    public function preCreate(PurchaseReturnDto $dto)
    {
        $originalBill = $this->purchaseService->findById($dto->purchase_id);
        $entity = PurchaseReturnMapper::dtoToEntity($dto, $originalBill);
        $entity->code = $this->getCode('RPU');
        $entity->original_bill_code = $originalBill->code;

        Log::info("Creating purchase return with original bill code {$entity->original_bill_code}");

        return $entity;
    }

    public function create($dto)
    {
        $entity = $this->preCreate($dto);

        return DB::transaction(function () use ($entity) {
            $created = $this->repo->create($entity);

            foreach ($entity->items as $item) {
                $item->purchase_return_id = $created->id;
                $this->purchaseReturnItemRepo->create($item);
            }

            foreach ($entity->items as $item) {
                $entryHistory = $this->stockService->findByBillNumberAndTypeAndProductId(
                    billNumber: $entity->original_bill_code,
                    productId: $item->product_id,
                    type: StockMovementType::ENTRY->value
                );

                $returnHistory = $this->stockMovementRepo->findByBillNumberAndTypeAndProductId(
                    billNumber: $entity->original_bill_code,
                    productId: $item->product_id,
                    type: StockMovementType::PURCHASE_RETURN->value
                );

                $history = $this->updateQuantity($entryHistory, $returnHistory);

                $this->stockService->handelBatchesMovement(
                    batches: $history,
                    quantity: $item->quantity,
                    callBack: function ($batch, $canTake) use ($created) {
                        $this->stockMovementRepo->adjust(
                            $batch->product_batch_id,
                            $created->store_id,
                            -$canTake,
                            StockMovementType::PURCHASE_RETURN,
                            $created->code
                        );
                    }
                );
            }

            return $created;
        });
    }

    public function getTodayReturnsCount(): int
    {
        return collect($this->repo->findAll())
            ->filter(fn($r) => $r->created_at?->isToday() ?? false)
            ->count();
    }

    public function getTodayReturnsTotal(): float
    {
        return collect($this->repo->findAll())
            ->filter(fn($r) => $r->created_at?->isToday() ?? false)
            ->sum(fn($r) => $r->grand_total ?? 0);
    }

    public function getRecentReturns(int $limit = 5): array
    {
        return collect($this->repo->findAll())
            ->reverse()
            ->take($limit)
            ->values()
            ->all();
    }

    private function updateQuantity($entryArray, $returnedArray)
    {
        $totalReturns = $returnedArray->groupBy('product_batch_id')
            ->map(fn($group) => $group->sum('quantity'));

        return $entryArray->map(function ($item) use ($totalReturns) {
            $batchId = $item->product_batch_id;

            if (isset($totalReturns[$batchId]) && $item->quantity < $totalReturns[$batchId]) {
                throw new \InvalidArgumentException('Returned quantity exceeds entry quantity');
            }
            if (isset($totalReturns[$batchId])) {
                $item->quantity -= $totalReturns[$batchId];
            }

            return $item;
        })->filter(fn($item) => $item->quantity > 0)->values();
    }
}
