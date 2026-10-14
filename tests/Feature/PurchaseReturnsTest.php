<?php

namespace Tests\Feature;

use App\Application\DTOs\PurchaseDto;
use App\Application\DTOs\PurchaseReturnDto;
use App\Application\DTOs\PurchaseReturnItemDto;
use App\Application\Services\PurchaseReturnService;
use App\Application\Services\PurchaseService;
use App\Application\Services\SalesService;
use App\Domain\Utils\ReturnedItemStatus;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\Purchase;
use App\Infrastructure\Persistence\Models\PurchaseReturn;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurchaseReturnsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_purchase_return_successfully_and_deducts_stock(): void
    {
        $purchaseService = app(PurchaseService::class);
        $purchaseReturnService = app(PurchaseReturnService::class);

        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        // 1. Create a purchase of 10 units
        $purchase = $purchaseService->create(new PurchaseDto(
            supplier_id: $supplier->id,
            store_id: $store->id,
            tax: 0,
            discount: 0,
            paper_total: 500.0,
            items: [
                ['product_id' => $product->id, 'quantity' => 10, 'price' => 50.0],
            ]
        ));

        $batch = ProductBatch::where('product_id', $product->id)->first();
        $this->assertEquals(10, $batch->remaining_quantity);

        // 2. Return 3 units to supplier
        $returnPayload = [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'reason' => 'Damaged on delivery',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ];

        $response = $this->post(route('purchase-returns.store'), $returnPayload);
        $response->assertRedirect(route('purchase-returns.index'));

        // Verify stock deducted from batch and location
        $this->assertEquals(7, $batch->fresh()->remaining_quantity);
        $this->assertEquals(
            7,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $store->id)
                ->value('remaining_quantity')
        );

        // Verify stock movement
        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch->id,
            'location_id' => $store->id,
            'quantity' => -3,
            'type' => StockMovementType::PURCHASE_RETURN->value,
        ]);
    }

    public function test_multi_time_purchase_returns_on_same_bill(): void
    {
        $purchaseService = app(PurchaseService::class);
        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        // Original purchase of 20 units
        $purchase = $purchaseService->create(new PurchaseDto(
            supplier_id: $supplier->id,
            store_id: $store->id,
            tax: 0,
            discount: 0,
            paper_total: 1000.0,
            items: [
                ['product_id' => $product->id, 'quantity' => 20, 'price' => 50.0],
            ]
        ));

        $batch = ProductBatch::where('product_id', $product->id)->first();
        $this->assertEquals(20, $batch->remaining_quantity);

        // Return 1: 8 units -> succeeds (remaining returnable: 12)
        $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 8,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertSessionHasNoErrors();
        $this->assertEquals(12, $batch->fresh()->remaining_quantity);

        // Return 2: 7 units -> succeeds (remaining returnable: 5)
        $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 7,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertSessionHasNoErrors();
        $this->assertEquals(5, $batch->fresh()->remaining_quantity);

        // Return 3: 6 units -> MUST FAIL (only 5 returnable)
        $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 6,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertSessionHasErrors(['items']);
        $this->assertEquals(5, $batch->fresh()->remaining_quantity);

        // Return 4: 5 units -> MUST SUCCEED (0 returnable left)
        $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertSessionHasNoErrors();
        $this->assertEquals(0, $batch->fresh()->remaining_quantity);

        // Return 5: 1 unit -> MUST FAIL
        $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertSessionHasErrors(['items']);
    }

    public function test_purchase_return_validation_errors(): void
    {
        $purchaseService = app(PurchaseService::class);
        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $productA = $this->createProduct();
        $productB = $this->createProduct();

        $purchase = $purchaseService->create(new PurchaseDto(
            supplier_id: $supplier->id,
            store_id: $store->id,
            tax: 0,
            discount: 0,
            paper_total: 500.0,
            items: [
                ['product_id' => $productA->id, 'quantity' => 10, 'price' => 50.0],
            ]
        ));

        // Product not in purchase bill
        $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $productB->id,
                    'quantity' => 2,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertSessionHasErrors(['items']);

        // Zero quantity
        $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $productA->id,
                    'quantity' => 0,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertSessionHasErrors(['items.0.quantity']);
    }

    public function test_purchase_returns_service_metrics(): void
    {
        $purchaseService = app(PurchaseService::class);
        $purchaseReturnService = app(PurchaseReturnService::class);

        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        $purchase = $purchaseService->create(new PurchaseDto(
            supplier_id: $supplier->id,
            store_id: $store->id,
            tax: 0,
            discount: 0,
            paper_total: 500.0,
            items: [
                ['product_id' => $product->id, 'quantity' => 10, 'price' => 50.0],
            ]
        ));

        $purchaseReturnService->create(new PurchaseReturnDto(
            purchase_id: $purchase->id,
            store_id: $store->id,
            items: [
                PurchaseReturnItemDto::create([
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ]),
            ]
        ));

        $this->assertEquals(1, $purchaseReturnService->getTodayReturnsCount());
        $this->assertGreaterThan(0, $purchaseReturnService->getTodayReturnsTotal());
        $this->assertCount(1, $purchaseReturnService->getRecentReturns(5));
    }

    public function test_purchase_return_fails_when_physical_stock_in_store_is_insufficient(): void
    {
        $purchaseService = app(PurchaseService::class);
        $salesService = app(SalesService::class);

        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);

        // 1. Purchase 10 units
        $purchase = $purchaseService->create(new PurchaseDto(
            supplier_id: $supplier->id,
            store_id: $store->id,
            tax: 0,
            discount: 0,
            paper_total: 500.0,
            items: [
                ['product_id' => $product->id, 'quantity' => 10, 'price' => 50.0],
            ]
        ));

        // 2. Sell 8 units to customers (leaving only 2 units physically in store)
        $salesService->create(new \App\Application\DTOs\SalesDto(
            customer_name: 'Store Customer',
            store_id: $store->id,
            type: \App\Infrastructure\Persistence\utils\PriceType::RETAIL,
            items: [
                \App\Application\DTOs\SalesItemDto::create([
                    'product_id' => $product->id,
                    'quantity' => 8,
                ]),
            ],
            discount: 0
        ));

        $batch = ProductBatch::where('product_id', $product->id)->first();
        $this->assertEquals(2, $batch->remaining_quantity);

        // 3. Attempt to return 5 units to supplier -> MUST FAIL (only 2 physically available)
        $response = $this->post(route('purchase-returns.store'), [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['items']);
        // Batch remaining quantity must NOT drop to negative
        $this->assertEquals(2, $batch->fresh()->remaining_quantity);
    }
}
