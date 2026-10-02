<?php

namespace App\Application\Services;

use App\Application\DTOs\SalesReturnsDto;
use App\Application\Mapper\SalesReturnsMapper;
use App\Domain\Entities\SalesReturnsEntity;
use App\Domain\Repo\SalesReturnItemRepo;
use App\Domain\Repo\SalesReturnsRepo;
use App\Domain\Repo\StockMovementRepo;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesReturnsService extends BaseService
{
    protected string $entityClass = SalesReturnsEntity::class;

    public function __construct(
        SalesReturnsRepo $repo,
        private SalesReturnItemRepo $salesReturnItemRepo,
        private SalesService $salesService,
        private StockService $stockService,
        private StockMovementRepo $stockMovementRepo,
    ) {
        $this->repo = $repo;
    }

    public function preCreate(SalesReturnsDto $dto)
    {
        $originalBill = $this->salesService->findById($dto->sales_id);
        $entity = SalesReturnsMapper::dtoToEntity($dto, $originalBill);
        $entity->code = $this->getCode('RSA');
        $entity->original_bill_code = $originalBill->code;

        Log::info("Creating sales return with original bill code {$entity->original_bill_code} and discount {$entity->discount} and tax {$entity->tax}");

        return $entity;
    }

    public function create($dto)
    {
        $entity = $this->preCreate($dto);

        return DB::transaction(
            function () use ($entity) {

                $created = $this->repo->create($entity);

                foreach ($entity->items as $item) {
                    $item->sales_return_id = $created->id;
                }
                $this->repo->insert($entity->items);


                foreach ($entity->items as $item) {
                    $saleHistory = $this
                        ->stockService
                        ->findByBillNumberAndTypeAndProductId(
                            billNumber: $entity->original_bill_code,
                            productId: $item->product_id,
                            type: StockMovementType::SALE->value
                        );

                    $returnHistory = $this->stockMovementRepo->findByBillNumberAndTypeAndProductId(
                        billNumber: $entity->original_bill_code,
                        productId: $item->product_id,
                        type: StockMovementType::SALE_RETURN->value
                    );

                    $history = $this->updateQuantity($saleHistory, $returnHistory);

                    $this->stockService->handelBatchesMovement(
                        batches: $history,
                        quantity: $item->quantity,
                        callBack: function ($batch, $canTake) use ($created) {
                            $this->stockMovementRepo->adjust(
                                $batch->product_batch_id,
                                $created->store_id,
                                $canTake,
                                StockMovementType::SALE_RETURN,
                                $created->code
                            );
                        }
                    );
                }

                return $created;
            }
        );
    }

    public function updateQuantity(
        $saleArray,
        $returnedArray
    ) {

        $totalReturns = $returnedArray->groupBy('product_batch_id')
            ->map(fn($group) => $group->sum('quantity'));

        return $saleArray->map(function ($item) use ($totalReturns) {
            $batchId = $item->product_batch_id;

            if ($item->quantity < $totalReturns[$batchId]) {
                throw new \InvalidArgumentException('returned quantity is more than sale quantity');
            }
            if (isset($totalReturns[$batchId])) {
                $item->quantity -= $totalReturns[$batchId];
            }

            return $item;
        })->filter(fn($item) => $item->quantity > 0)->values();
    }
}
