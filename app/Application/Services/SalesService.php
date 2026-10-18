<?php

namespace App\Application\Services;

use App\Domain\Entities\SalesEntity;
use App\Domain\Entities\SalesItemEntity;
use App\Domain\Repo\ProductPriceRepo;
use App\Domain\Repo\SalesItemRepo;
use App\Domain\Repo\SalesRepo;
use Illuminate\Support\Facades\DB;

class SalesService extends BaseService
{
    protected string $entityClass = SalesEntity::class;

    public function __construct(
        SalesRepo $repo,
        private SalesItemRepo $salesItemsRepo,
        private ProductPriceRepo $productPriceRepo,
        private StockService $stockService
    ) {
        $this->repo = $repo;
    }

    public function preCreate($dto)
    {
        $items = collect($dto->items)->map(function ($item) use ($dto) {
            $price = $this->productPriceRepo->
                getByProductIdAndType(productId: $item->product_id, type: $dto->type)->price;

            return SalesItemEntity::create(data: $item->toArray(), price: $price);
        });

        return SalesEntity::create($dto->toArray(), $items->all());
    }

    public function create($dto)
    {
        $entity = $this->preCreate($dto);
        $entity->code = $this->getCode('SAL');

        return DB::transaction(
            function () use ($entity) {

                $created = $this->repo->create($entity);

                $created->items = collect($entity->items)->each(function ($item) use ($created) {
                    $item->bill_id = $created->id;
                    $this->salesItemsRepo->create($item);

                    // validate quantity
                    // update stock(product Batch , patch location)
                    $this->stockService->sale(
                        productId: $item->product_id,
                        quantity: $item->quantity,
                        locationId: $created->store_id,
                        billNumber: $created->code
                    );
                })->all();

                return $created;
            }
        );
    }

    public function getTodaySalesCount(): int
    {
        return collect($this->repo->findAll())
            ->filter(fn ($sale) => $sale->created_at?->isToday() ?? false)
            ->count();
    }

    public function getTodaySalesTotal(): float
    {
        return collect($this->repo->findAll())
            ->filter(fn ($sale) => $sale->created_at?->isToday() ?? false)
            ->sum(fn ($sale) => $sale->grand_total ?? 0);
    }

    public function getRecentSales(int $limit = 5): array
    {
        return collect($this->repo->findAll())
            ->reverse()
            ->take($limit)
            ->values()
            ->all();
    }
}
