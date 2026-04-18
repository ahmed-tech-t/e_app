<?php
namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\SalesItemMapper;
use App\Domain\Repo\SalesItemRepo;
use App\Infrastructure\Persistence\Models\SalesItem;


class ESalesItemRepo extends BaseERepo implements SalesItemRepo
{
    protected $modelClass = SalesItem::class;
    protected $mapper = SalesItemMapper::class;

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];

    /**
     * @inheritDoc
     */
    public function getItemQuantityInBill(int $saleId, int $productId): int
    {
        $item = SalesItem::where('bill_id', $saleId)
            ->where('product_id', $productId)
            ->first();

        return $item->quantity ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function getItemsQuantitiesInBill(int $saleId, array $productIds): array
    {
        return SalesItem::where('bill_id', $saleId)
            ->whereIn('product_id', $productIds)
            ->pluck('quantity', 'product_id')
            ->toArray();
    }
}