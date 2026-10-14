<?php

namespace App\Infrastructure\Persistence\repo;

use App\Application\Mapper\PurchaseReturnItemMapper;
use App\Domain\Repo\PurchaseReturnItemRepo;
use App\Infrastructure\Persistence\Models\PurchaseReturnItem;
use Illuminate\Support\Facades\DB;

class EPurchaseReturnItemRepo extends BaseERepo implements PurchaseReturnItemRepo
{
    protected $modelClass = PurchaseReturnItem::class;
    protected $mapper = PurchaseReturnItemMapper::class;

    protected array $searchFilters = [];
    protected array $withForPaginate = [];
    protected array $defaultRelationships = [];

    public function getReturnedItemsQuantitiesInBill(int $purchaseId, array $productIds): array
    {
        return DB::table('purchase_return_items')
            ->join('purchase_returns', 'purchase_returns.id', '=', 'purchase_return_items.purchase_return_id')
            ->where('purchase_returns.original_bill_id', $purchaseId)
            ->whereIn('purchase_return_items.product_id', $productIds)
            ->select('purchase_return_items.product_id', DB::raw('SUM(purchase_return_items.quantity) as total_returned'))
            ->groupBy('purchase_return_items.product_id')
            ->pluck('total_returned', 'product_id')
            ->toArray();
    }
}
