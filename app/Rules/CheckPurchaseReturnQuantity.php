<?php

namespace App\Rules;

use App\Domain\Repo\ProductRepo;
use App\Domain\Repo\PurchaseItemRepo;
use App\Domain\Repo\PurchaseReturnItemRepo;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPurchaseReturnQuantity implements ValidationRule
{
    public function __construct(
        private $purchaseId,
        private $storeId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $purchaseItemRepo = app(PurchaseItemRepo::class);
        $purchaseReturnItemRepo = app(PurchaseReturnItemRepo::class);
        $productRepo = app(ProductRepo::class);
        $productBatchRepo = app(\App\Domain\Repo\ProductBatchRepo::class);

        $items = $value;
        $productIds = collect($items)->pluck('product_id')->toArray();

        $originalQuantities = $purchaseItemRepo->getItemsQuantitiesInBill($this->purchaseId, $productIds);
        $returnedQuantities = $purchaseReturnItemRepo->getReturnedItemsQuantitiesInBill($this->purchaseId, $productIds);

        $requestedTotals = collect($items)
            ->groupBy('product_id')
            ->map(fn ($group) => $group->sum('quantity'));

        foreach ($requestedTotals as $pid => $requestedQty) {
            $original = $originalQuantities[$pid] ?? 0;
            $returned = $returnedQuantities[$pid] ?? 0;
            $available = $original - $returned;

            $product = $productRepo->findById($pid);
            $name = $product->name_ar ?? $product->name_en ?? $product->code;

            if ($requestedQty > $available) {
                $fail("The return quantity for product '{$name}' cannot exceed the available quantity of {$available}.");

                return;
            }

            if ($this->storeId) {
                $physicalStock = (int) $productBatchRepo->getProductQuantityInLocation($pid, $this->storeId);
                if ($requestedQty > $physicalStock) {
                    $fail("The return quantity for product '{$name}' cannot exceed current physical stock in store ({$physicalStock}).");

                    return;
                }
            }
        }
    }
}
