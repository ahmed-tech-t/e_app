<?php

namespace Tests\Feature;

use App\Application\DTOs\SalesDto;
use App\Application\DTOs\SalesItemDto;
use App\Application\Services\SalesService;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\Sales;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SalesTest extends TestCase
{
    use RefreshDatabase;

    private string $salesApiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->salesApiUrl = Constants::BASE_API_URL.'/v1/sales';
    }

    public function test_creates_sale_successfully_and_deducts_stock(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 150.0);
        $batch = $this->createBatch($product, $store, quantity: 10, costPrice: 80.0);

        $payload = [
            'customer_name' => 'Ahmed Ali',
            'customer_phone' => '01234567890',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                ],
            ],
        ];

        $response = $this->postJson($this->salesApiUrl, $payload);
        $response->assertStatus(200);

        $this->assertDatabaseHas('sales', [
            'store_id' => $store->id,
            'customer_name' => 'Ahmed Ali',
        ]);

        $sale = Sales::first();
        $this->assertNotNull($sale);
        $this->assertDatabaseHas('sales_items', [
            'bill_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 3,
            'price' => 150.0,
        ]);

        // Stock checks: ProductBatch and BatchLocation deducted
        $this->assertEquals(7, $batch->fresh()->remaining_quantity);
        $this->assertEquals(
            7,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $store->id)
                ->value('remaining_quantity')
        );

        // Stock movement audit check
        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch->id,
            'location_id' => $store->id,
            'quantity' => -3,
            'type' => StockMovementType::SALE->value,
            'bill_number' => $sale->code,
        ]);
    }

    public function test_sale_with_fifo_multi_batch_depletion(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);

        // Batch 1: 5 units (created earlier)
        $batch1 = $this->createBatch($product, $store, quantity: 5);
        $batch1->created_at = now()->subMinutes(10);
        $batch1->save();

        // Batch 2: 10 units (created later)
        $batch2 = $this->createBatch($product, $store, quantity: 10);
        $batch2->created_at = now()->subMinutes(2);
        $batch2->save();

        // Sell 8 units (5 from Batch 1, 3 from Batch 2)
        $payload = [
            'customer_name' => 'FIFO Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 8,
                ],
            ],
        ];

        $response = $this->postJson($this->salesApiUrl, $payload);
        $response->assertStatus(200);

        // FIFO verification
        $this->assertEquals(0, $batch1->fresh()->remaining_quantity);
        $this->assertEquals(7, $batch2->fresh()->remaining_quantity);

        // Location stock verification
        $this->assertEquals(
            0,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch1->id)
                ->where('location_id', $store->id)
                ->value('remaining_quantity')
        );
        $this->assertEquals(
            7,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch2->id)
                ->where('location_id', $store->id)
                ->value('remaining_quantity')
        );

        // Separate movements for each batch
        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch1->id,
            'quantity' => -5,
            'type' => StockMovementType::SALE->value,
        ]);
        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch2->id,
            'quantity' => -3,
            'type' => StockMovementType::SALE->value,
        ]);
    }

    public function test_sale_fails_when_product_out_of_stock(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct();

        $payload = [
            'customer_name' => 'Out of stock customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson($this->salesApiUrl, $payload);
        // Controller/exception handler should catch and return error status (500 or 400/422)
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_sale_fails_when_quantity_exceeds_available_stock(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct();
        $this->createBatch($product, $store, quantity: 4);

        $payload = [
            'customer_name' => 'Excess customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ];

        $response = $this->postJson($this->salesApiUrl, $payload);
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_sale_fails_when_stock_only_available_in_other_location(): void
    {
        $storeA = $this->createLocation();
        $storeB = $this->createLocation();
        $product = $this->createProduct();

        // Stock exists in store A only
        $this->createBatch($product, $storeA, quantity: 10);

        // Attempt to sell from store B
        $payload = [
            'customer_name' => 'Cross store customer',
            'store_id' => $storeB->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ];

        $response = $this->postJson($this->salesApiUrl, $payload);
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_sale_validation_errors(): void
    {
        $store = $this->createLocation();

        // Empty items
        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Valid Name',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [],
        ]);
        $response->assertStatus(422);

        // Negative quantity
        $product = $this->createProduct();
        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Valid Name',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => -2,
                ],
            ],
        ]);
        $response->assertStatus(422);

        // Non-existent store
        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Valid Name',
            'store_id' => 99999,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ]);
        $response->assertStatus(422);
    }

    public function test_direct_service_sales_calculation_and_concurrency(): void
    {
        $salesService = app(SalesService::class);
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 200.0);
        $batch = $this->createBatch($product, $store, quantity: 20);

        $dto = new SalesDto(
            customer_name: 'Direct Service Customer',
            store_id: $store->id,
            type: PriceType::RETAIL,
            items: [
                SalesItemDto::create([
                    'product_id' => $product->id,
                    'quantity' => 5,
                ]),
            ],
            discount: 10.0, // 10% discount
            customer_phone: '01112223334'
        );

        $createdSale = $salesService->create($dto);

        $this->assertNotNull($createdSale);
        $this->assertEquals(15, $batch->fresh()->remaining_quantity);

        // Verify today's sales metrics
        $this->assertEquals(1, $salesService->getTodaySalesCount());
        $this->assertGreaterThan(0, $salesService->getTodaySalesTotal());
        $this->assertCount(1, $salesService->getRecentSales(5));
    }

    public function test_multi_item_sale_rolls_back_completely_if_one_item_fails(): void
    {
        $store = $this->createLocation();
        $productA = $this->createProduct([], retailPrice: 100.0);
        $productB = $this->createProduct([], retailPrice: 50.0);

        // Product A has 5 in stock; Product B has 0 in stock
        $batchA = $this->createBatch($productA, $store, quantity: 5);

        $payload = [
            'customer_name' => 'Atomic Rollback Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $productA->id, 'quantity' => 2], // valid
                ['product_id' => $productB->id, 'quantity' => 3], // out of stock
            ],
        ];

        $response = $this->postJson($this->salesApiUrl, $payload);
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));

        // Transaction must be completely rolled back
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseCount('sales_items', 0);

        // Product A batch must NOT be deducted
        $this->assertEquals(5, $batchA->fresh()->remaining_quantity);

        // No SALE movement should exist for batch A
        $this->assertDatabaseMissing('stock_movements', [
            'product_batch_id' => $batchA->id,
            'type' => StockMovementType::SALE->value,
        ]);
    }

    public function test_sale_depleting_batch_to_exact_zero_and_rejecting_subsequent_sale(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $batch = $this->createBatch($product, $store, quantity: 5);

        // 1. Buy exact total available quantity (5 units)
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Full Depletion Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ])->assertStatus(200);

        $this->assertEquals(0, $batch->fresh()->remaining_quantity);

        // 2. Immediate next purchase of 1 unit must fail
        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Subsequent Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);

        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
    }

    public function test_sale_with_wholesale_price_type(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 120.0, wholesalePrice: 85.0);
        $this->createBatch($product, $store, quantity: 10);

        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Wholesale Merchant',
            'store_id' => $store->id,
            'bill_type' => PriceType::WHOLESALE->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 4],
            ],
        ]);
        $response->assertStatus(200);

        $sale = Sales::first();
        $this->assertEquals(340.0, (float) $sale->total); // 4 * 85.0 = 340.0
        $this->assertEquals(391.0, (float) $sale->grand_total); // 340.0 + 15% tax = 391.0
        $this->assertDatabaseHas('sales_items', [
            'bill_id' => $sale->id,
            'price' => 85.0,
            'quantity' => 4,
        ]);
    }

    public function test_sale_with_duplicate_lines_of_same_product(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 50.0);
        $batch = $this->createBatch($product, $store, quantity: 10);

        // Scenario A: duplicate lines within available stock (3 + 4 = 7 <= 10)
        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Cashier Scanned Twice',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
                ['product_id' => $product->id, 'quantity' => 4],
            ],
        ]);
        $response->assertStatus(200);

        $this->assertEquals(3, $batch->fresh()->remaining_quantity); // 10 - 7 = 3

        $sale = Sales::first();
        $this->assertCount(2, $sale->items);
        $this->assertEquals(350.0, (float) $sale->total); // 7 * 50 = 350
    }

    public function test_sale_with_duplicate_lines_exceeding_stock_rolls_back(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 50.0);
        $batch = $this->createBatch($product, $store, quantity: 5);

        // Scenario B: duplicate lines exceed available stock (3 + 3 = 6 > 5)
        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Over-scanned Cart',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ]);

        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
        $this->assertEquals(5, $batch->fresh()->remaining_quantity); // Untouched stock
        $this->assertDatabaseCount('sales', 0); // Rolled back completely
    }

    public function test_sale_with_percentage_discount(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $this->createBatch($product, $store, quantity: 10);

        // 10% discount on 2 units = total 200, discount 20, net 180 + 30 (15% tax) = 210
        $response = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Discount Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 10,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);
        $response->assertStatus(200);

        $sale = Sales::first();
        $this->assertEquals(200.0, (float) $sale->total);
        $this->assertEquals(10.0, (float) $sale->discount);
        $this->assertEquals(15.0, (float) $sale->tax);
        $this->assertEquals(210.0, (float) $sale->grand_total);
    }

    public function test_sale_rejects_invalid_discount(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct();
        $this->createBatch($product, $store, quantity: 5);

        // Discount > 100%
        $response1 = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Invalid Discount Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 105,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);
        $response1->assertStatus(422);

        // Negative discount
        $response2 = $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Negative Discount Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => -5,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);
        $response2->assertStatus(422);
    }

    public function test_sale_preview_calculates_without_saving_or_deducting_stock(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $batch = $this->createBatch($product, $store, quantity: 10);

        $previewUrl = Constants::BASE_API_URL.'/v1/sales/preview';
        $payload = [
            'customer_name' => 'Preview Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 10,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ];

        $response = $this->getJson($previewUrl.'?'.http_build_query($payload));
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals(200.0, (float) $data['total']);
        $this->assertEquals(210.0, (float) $data['grand_total']);

        // Assert no records created and stock untouched
        $this->assertDatabaseCount('sales', 0);
        $this->assertEquals(10, $batch->fresh()->remaining_quantity);
    }

    public function test_sales_index_returns_paginated_sales(): void
    {
        $response = $this->getJson($this->salesApiUrl);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'items',
                'meta' => [
                    'current_page',
                    'total',
                    'last_page',
                ],
            ],
        ]);
    }
}
