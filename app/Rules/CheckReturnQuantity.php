<?php

namespace App\Rules;

use App\Domain\Repo\ProductRepo;
use App\Domain\Repo\SalesItemRepo;
use App\Domain\Repo\SalesReturnItemRepo;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckReturnQuantity implements ValidationRule
{

    protected $saleId;

    public function __construct($saleId)
    {
        $this->saleId = $saleId;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $salesItemRepo = app(SalesItemRepo::class);
        $salesReturnItemRepo = app(SalesReturnItemRepo::class);
        $productRepo = app(ProductRepo::class);

        $items = $value;
        $productIds = collect($items)->pluck('product_id')->toArray();

        $originalQuantities = $salesItemRepo->getItemsQuantitiesInBill($this->saleId, $productIds);
        $returnedQuantities = $salesReturnItemRepo->getReturnedItemsQuantitiesInBill($this->saleId, $productIds);

        foreach ($items as $item) {
            $pid = $item['product_id'];
            $requestedQty = $item['quantity'];
            $original = $originalQuantities[$pid] ?? 0;
            $returned = $returnedQuantities[$pid] ?? 0;
            $available = $original - $returned;

            if ($requestedQty > $available) {
                $product = $productRepo->findById($pid);
                $name = $product->name_ar ?? $product->name_en ?? $product->code;
                $fail("The return quantity for product ID { $name } cannot exceed the available quantity of {$available}.");
                return;
            }
        }
    }
}
