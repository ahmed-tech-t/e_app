<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\SalesReturnItemMapper;
use App\Domain\Repo\SalesReturnItemRepo;
use App\Infrastructure\Persistence\Models\SalesReturnItem;
use Illuminate\Support\Facades\DB;

class ESalesReturnItemRepo extends BaseERepo implements SalesReturnItemRepo
{
    protected $modelClass = SalesReturnItem::class;

    protected $mapper = SalesReturnItemMapper::class;

    // protected $queryContext = ;

    protected array $searchFilters = [];

    protected array $withForPaginate = [];

    protected array $defaultRelationships = [];

    /**
     * {@inheritDoc}
     */
    public function getReturnedItemQuantityInBill(int $saleId, int $productId): int
    {
        $returnedQuantity = SalesReturnItem::whereHas(
            'salesReturn',
            function ($query) use ($saleId) {

                $query->where('sale_id', $saleId);

            }
        )
            ->where('product_id', $productId)
            ->sum('quantity')
            ->lockForUpdate();

        return $returnedQuantity ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getReturnedItemsQuantitiesInBill(int $saleId, array $productIds): array
    {
        return DB::table('sales_return_items')
            ->join('sales_returns', 'sales_returns.id', '=', 'sales_return_items.sales_return_id')
            ->where('sales_returns.original_bill_id', $saleId)
            ->whereIn('sales_return_items.product_id', $productIds)
            ->select('sales_return_items.product_id', DB::raw('SUM(sales_return_items.quantity) as total_returned'))
            ->groupBy('sales_return_items.product_id')
            ->pluck('total_returned', 'product_id')
            ->toArray();
    }
}
