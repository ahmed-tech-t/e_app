<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Application\DTOs\PurchaseDto;
use App\Application\Services\PurchaseService;
use App\Domain\Entities\PurchaseEntity;
use App\Infrastructure\Persistence\Models\Location;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\Supplier;
use App\Infrastructure\Persistence\utils\PriceType;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Location::count() === 0) {
            $this->call(LocationSeeder::class);
        }

        if (Supplier::count() === 0) {
            $this->call(SupplierSeeder::class);
        }

        if (Product::count() === 0) {
            $this->call(ProductSeeder::class);
        }

        $stores = Location::where('type', 'store')->get();
        if ($stores->isEmpty()) {
            $stores = Location::all();
        }

        $suppliers = Supplier::all();
        $products = Product::with('prices')->get();

        if ($products->isEmpty() || $stores->isEmpty() || $suppliers->isEmpty()) {
            return;
        }

        $purchaseService = app(PurchaseService::class);

        // Partition products into 20 groups so that all products are covered across 20 purchases.
        // Each product gets at least 50 in quantity.
        $totalPurchases = 20;
        $productCount = $products->count();
        $chunkSize = (int) ceil($productCount / $totalPurchases);
        $productChunks = $products->chunk($chunkSize)->values();

        for ($i = 0; $i < $totalPurchases; $i++) {
            $store = $stores->random();
            $supplier = $suppliers->random();

            $chunkProducts = $productChunks->get($i, collect());
            if ($chunkProducts->isEmpty()) {
                $chunkProducts = $products->random(min(5, $productCount));
            }

            $items = [];
            $subtotal = 0.0;

            foreach ($chunkProducts as $product) {
                // Minimum quantity 50
                $quantity = rand(50, 75);

                $wholesalePrice = (float) ($product->prices->firstWhere('type', PriceType::WHOLESALE->value)?->price ?? 50.0);
                if ($wholesalePrice <= 0) {
                    $wholesalePrice = 50.0;
                }
                $costPrice = max(1.0, round($wholesalePrice * 0.75, 2));

                $lineTotal = round($quantity * $costPrice, 2);
                $subtotal += $lineTotal;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $costPrice,
                ];
            }

            $subtotal = round($subtotal, 2);
            $taxPercent = 5.0;
            $discountPercent = 0.0;
            $grandTotal = PurchaseEntity::getGrandTotal($subtotal, $discountPercent, $taxPercent);

            $dto = new PurchaseDto(
                supplier_id: $supplier->id,
                store_id: $store->id,
                discount: $discountPercent,
                tax: $taxPercent,
                items: $items,
                paper_total: $grandTotal,
            );

            $purchaseService->create($dto);
        }
    }
}
