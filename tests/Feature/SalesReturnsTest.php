<?php

namespace Tests\Feature;

use App\Application\DTOs\SalesDto;
use App\Application\DTOs\SalesItemDto;
use App\Application\DTOs\SalesReturnItemDto;
use App\Application\DTOs\SalesReturnsDto;
use App\Application\Services\SalesReturnsService;
use App\Application\Services\SalesService;
use App\Domain\Utils\ReturnedItemStatus;
use App\Infrastructure\Persistence\Models\Sales;
use App\Infrastructure\Persistence\Models\SalesReturns;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SalesReturnsTest extends TestCase
{
    use RefreshDatabase;

    private string $salesApiUrl;
    private string $returnsApiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->salesApiUrl = Constants::BASE_API_URL . '/v1/sales';
        $this->returnsApiUrl = Constants::BASE_API_URL . '/v1/sales/returns';
    }

    public function test_creates_sales_return_successfully_and_restocks_batch_and_location(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 120.0);
        $batch = $this->createBatch($product, $store, quantity: 10);

        // 1. Perform a sale of 5 items
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'John Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();
        $this->assertEquals(5, $batch->fresh()->remaining_quantity);

        // 2. Return 2 items
        $returnPayload = [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'reason' => 'Defective',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ];

        $response = $this->postJson($this->returnsApiUrl, $returnPayload);
        $response->assertStatus(200);

        // Verify SalesReturns record
        $this->assertDatabaseHas('sales_returns', [
            'original_bill_id' => $sale->id,
            'location_id' => $store->id,
        ]);

        $salesReturn = SalesReturns::first();
        $this->assertNotNull($salesReturn);

        // Verify restock in ProductBatch and BatchLocations
        $this->assertEquals(7, $batch->fresh()->remaining_quantity);
        $this->assertEquals(
            7,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $store->id)
                ->value('remaining_quantity')
        );

        // Verify StockMovement audit
        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch->id,
            'location_id' => $store->id,
            'quantity' => 2,
            'type' => StockMovementType::SALE_RETURN->value,
            'bill_number' => $salesReturn->code,
        ]);
    }

    public function test_multi_time_returns_on_same_sale_bill(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $batch = $this->createBatch($product, $store, quantity: 20);

        // Original sale of 10 items
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Multi Return Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 10],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();
        $this->assertEquals(10, $batch->fresh()->remaining_quantity);

        // 1st Return: 4 items (allowed: 10, remaining returnable: 6)
        $resp1 = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 4,
                    'reason' => 'Part 1 return',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $resp1->assertStatus(200);
        $this->assertEquals(14, $batch->fresh()->remaining_quantity);

        // 2nd Return: 3 items (allowed: 6, remaining returnable: 3)
        $resp2 = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'reason' => 'Part 2 return',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $resp2->assertStatus(200);
        $this->assertEquals(17, $batch->fresh()->remaining_quantity);

        // 3rd Return: 4 items -> MUST FAIL (only 3 items remain returnable!)
        $resp3 = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 4,
                    'reason' => 'Over-return attempt',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $resp3->assertStatus(422);

        // 4th Return: exactly 3 items -> MUST SUCCEED (now 10/10 returned)
        $resp4 = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'reason' => 'Part 3 return final',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $resp4->assertStatus(200);
        $this->assertEquals(20, $batch->fresh()->remaining_quantity);

        // 5th Return: 1 item -> MUST FAIL (0 items remaining returnable)
        $resp5 = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'reason' => 'Beyond total return',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $resp5->assertStatus(422);
    }

    public function test_return_on_multi_batch_sale(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);

        // Batch 1: 5 units (older)
        $batch1 = $this->createBatch($product, $store, quantity: 5);
        $batch1->created_at = now()->subMinutes(10);
        $batch1->save();

        // Batch 2: 10 units (newer)
        $batch2 = $this->createBatch($product, $store, quantity: 10);
        $batch2->created_at = now()->subMinutes(2);
        $batch2->save();

        // Sell 8 units (5 from Batch 1, 3 from Batch 2)
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Multi Batch Sale Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 8],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();
        $this->assertEquals(0, $batch1->fresh()->remaining_quantity);
        $this->assertEquals(7, $batch2->fresh()->remaining_quantity);

        // Return 6 units (should replenish batches up to their sold amount)
        $response = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 6,
                    'reason' => 'Multi batch return',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $response->assertStatus(200);

        // Total stock across batches should now be: initial (15) - sold (8) + returned (6) = 13
        $totalRemaining = $batch1->fresh()->remaining_quantity + $batch2->fresh()->remaining_quantity;
        $this->assertEquals(13, $totalRemaining);
    }

    public function test_sales_return_fails_when_product_not_in_sale_bill(): void
    {
        $store = $this->createLocation();
        $productA = $this->createProduct();
        $productB = $this->createProduct();

        $this->createBatch($productA, $store, quantity: 10);
        $this->createBatch($productB, $store, quantity: 10);

        // Sale with Product A only
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Product A Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $productA->id, 'quantity' => 3],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();

        // Return Product B on this bill -> Should fail validation
        $response = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $productB->id,
                    'quantity' => 1,
                    'reason' => 'Wrong product',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);

        $response->assertStatus(422);
    }

    public function test_sales_return_fails_with_invalid_quantities(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct();
        $this->createBatch($product, $store, quantity: 10);

        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Valid Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();

        // Zero quantity
        $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 0,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertStatus(422);

        // Negative quantity
        $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => -1,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ])->assertStatus(422);
    }

    public function test_sales_returns_service_metrics(): void
    {
        $salesService = app(SalesService::class);
        $returnsService = app(SalesReturnsService::class);

        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $this->createBatch($product, $store, quantity: 10);

        $sale = $salesService->create(new SalesDto(
            customer_name: 'Metrics Customer',
            store_id: $store->id,
            type: PriceType::RETAIL,
            items: [
                SalesItemDto::create(['product_id' => $product->id, 'quantity' => 4]),
            ],
            discount: 0
        ));

        $returnsService->create(new SalesReturnsDto(
            sales_id: $sale->id,
            store_id: $store->id,
            items: [
                SalesReturnItemDto::create([
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'reason' => 'Metrics Return',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ]),
            ]
        ));

        $this->assertEquals(1, $returnsService->getTodayReturnsCount());
        $this->assertGreaterThan(0, $returnsService->getTodayReturnsTotal());
        $this->assertCount(1, $returnsService->getRecentReturns(5));
    }

    public function test_multi_product_return_in_single_bill(): void
    {
        $store = $this->createLocation();
        $productA = $this->createProduct([], retailPrice: 100.0);
        $productB = $this->createProduct([], retailPrice: 50.0);

        $batchA = $this->createBatch($productA, $store, quantity: 10);
        $batchB = $this->createBatch($productB, $store, quantity: 10);

        // Sale: 5 of Product A, 4 of Product B
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Multi Product Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $productA->id, 'quantity' => 5],
                ['product_id' => $productB->id, 'quantity' => 4],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();
        $this->assertEquals(5, $batchA->fresh()->remaining_quantity);
        $this->assertEquals(6, $batchB->fresh()->remaining_quantity);

        // Return 2 of Product A and 3 of Product B in one return invoice
        $response = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $productA->id,
                    'quantity' => 2,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
                [
                    'product_id' => $productB->id,
                    'quantity' => 3,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $response->assertStatus(200);

        // Verify both batches restocked
        $this->assertEquals(7, $batchA->fresh()->remaining_quantity); // 5 + 2 = 7
        $this->assertEquals(9, $batchB->fresh()->remaining_quantity); // 6 + 3 = 9

        // Verify two return items created
        $return = SalesReturns::first();
        $this->assertDatabaseCount('sales_return_items', 2);
    }

    public function test_sales_return_rejects_duplicate_product_rows_in_payload_exceeding_total_available(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $this->createBatch($product, $store, quantity: 10);

        // Sale of 4 units
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Duplicate Row Test Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 4],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();

        // Malicious or buggy client sends two rows for the same product with qty 3 each (total 6 > 4)
        $response = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);

        $response->assertStatus(422);
    }

    public function test_sales_return_preserves_original_discount_and_tax(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $this->createBatch($product, $store, quantity: 10);

        // Sale with 20% discount
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Discounted Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 20, // 20% discount
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();
        $this->assertEquals(20.0, (float) $sale->discount);

        // Return 1 unit ($100 gross)
        $response = $this->postJson($this->returnsApiUrl, [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $response->assertStatus(200);

        $salesReturn = SalesReturns::first();
        $this->assertEquals(100.0, (float) $salesReturn->total);
        $this->assertEquals(20.0, (float) $salesReturn->discount);
        // Refund grand total: 100 - 20 (discount) + 15 (VAT on 100) = 95.0
        $this->assertEquals(95.0, (float) $salesReturn->grand_total);
    }

    public function test_sales_return_preview_calculates_without_saving(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $batch = $this->createBatch($product, $store, quantity: 10);

        // Make a sale of 2 units with 10% discount
        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Preview Sale Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 10,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();

        $previewUrl = Constants::BASE_API_URL . '/v1/sales/returns/preview';
        $payload = [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ];

        $response = $this->getJson($previewUrl . '?' . http_build_query($payload));
        $response->assertStatus(200);

        $this->assertEquals(100.0, (float) $response->json('data.total'));
        // Refund calculation: 100 - 10 (10% discount) + 15 (tax) = 105
        $this->assertEquals(105.0, (float) $response->json('data.grand_total'));

        // No returns recorded yet
        $this->assertDatabaseCount('sales_returns', 0);
        // Stock remains 8 (10 - 2)
        $this->assertEquals(8, $batch->fresh()->remaining_quantity);
    }

    public function test_sales_return_allows_returning_single_item_from_multi_item_sale_with_empty_items(): void
    {
        $store = $this->createLocation();
        $product1 = $this->createProduct([], retailPrice: 100.0);
        $product2 = $this->createProduct([], retailPrice: 50.0);
        $batch1 = $this->createBatch($product1, $store, quantity: 10);
        $batch2 = $this->createBatch($product2, $store, quantity: 10);

        $this->postJson($this->salesApiUrl, [
            'customer_name' => 'Partial Return Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $product1->id, 'quantity' => 5],
                ['product_id' => $product2->id, 'quantity' => 5],
            ],
        ])->assertStatus(200);

        $sale = Sales::first();

        // Return only product1 with quantity 2, leaving product2 quantity empty
        $response = $this->post(route('sales-returns.store'), [
            'sales_id' => $sale->id,
            'store_id' => $store->id,
            'items' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => '2',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => '',
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('sales-returns.index'));

        // Product 1: 10 - 5 + 2 = 7
        $this->assertEquals(7, $batch1->fresh()->remaining_quantity);
        // Product 2: 10 - 5 = 5 (untouched by return)
        $this->assertEquals(5, $batch2->fresh()->remaining_quantity);
    }
}

