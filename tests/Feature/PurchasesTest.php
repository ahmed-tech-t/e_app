<?php

namespace Tests\Feature;

use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\Purchase;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PurchasesTest extends TestCase
{
    use RefreshDatabase;

    private string $purchaseApiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->purchaseApiUrl = Constants::BASE_API_URL.'/v1/purchases';
    }

    public function test_creates_purchase_successfully_and_records_batch_and_stock_entry(): void
    {
        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        $payload = [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
            'tax' => 5,
            'discount' => 0,
            'paper_total' => 1575.0,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 25,
                    'price' => 60.0,
                ],
            ],
        ];

        $response = $this->postJson($this->purchaseApiUrl, $payload);
        $response->assertStatus(200);

        // Verify purchase and purchase items
        $this->assertDatabaseHas('purchases', [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
        ]);

        $purchase = Purchase::first();
        $this->assertNotNull($purchase);

        $this->assertDatabaseHas('purchase_items', [
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 25,
            'price' => 60.0,
        ]);

        // Verify batch auto-created with purchase code
        $batch = ProductBatch::where('product_id', $product->id)->first();
        $this->assertNotNull($batch);
        $this->assertEquals(25, $batch->initial_quantity);
        $this->assertEquals(25, $batch->remaining_quantity);
        $this->assertEquals(60.0, (float) $batch->cost_price);

        // Verify store stock in batch_locations
        $this->assertEquals(
            25,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $store->id)
                ->value('remaining_quantity')
        );

        // Verify stock movement ENTRY
        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch->id,
            'location_id' => $store->id,
            'quantity' => 25,
            'type' => StockMovementType::ENTRY->value,
            'bill_number' => $purchase->code,
        ]);
    }

    public function test_purchase_validation_failures(): void
    {
        $store = $this->createLocation();

        // Invalid supplier
        $this->postJson($this->purchaseApiUrl, [
            'supplier_id' => 99999,
            'store_id' => $store->id,
            'paper_total' => 100,
            'items' => [
                ['product_id' => 1, 'quantity' => 5, 'price' => 20],
            ],
        ])->assertStatus(422);

        // Negative quantity
        $supplier = $this->createSupplier();
        $product = $this->createProduct();
        $this->postJson($this->purchaseApiUrl, [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
            'paper_total' => 100,
            'items' => [
                ['product_id' => $product->id, 'quantity' => -5, 'price' => 20],
            ],
        ])->assertStatus(422);

        // Negative price
        $this->postJson($this->purchaseApiUrl, [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
            'paper_total' => 100,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5, 'price' => -20],
            ],
        ])->assertStatus(422);
    }

    public function test_purchase_fails_when_paper_total_mismatches_system_calculation(): void
    {
        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        $payload = [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
            'tax' => 10,
            'discount' => 0,
            'paper_total' => 500.0, // System total would be 100 * 10 + 10% = 1100
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'price' => 100.0,
                ],
            ],
        ];

        $response = $this->postJson($this->purchaseApiUrl, $payload);
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
        $this->assertDatabaseCount('purchases', 0);
    }

    public function test_purchase_preview_calculates_without_saving(): void
    {
        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        $previewUrl = Constants::BASE_API_URL.'/v1/purchases/preview';
        $payload = [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
            'tax' => 10,
            'discount' => 5,
            'paper_total' => 1050.0,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'price' => 100.0,
                ],
            ],
        ];

        $response = $this->getJson($previewUrl.'?'.http_build_query($payload));
        $response->assertStatus(200);

        $this->assertEquals(1000.0, (float) $response->json('data.total'));
        $this->assertEquals(1050.0, (float) $response->json('data.grand_total'));

        // DB remains empty
        $this->assertDatabaseCount('purchases', 0);
        $this->assertDatabaseCount('product_batches', 0);
    }
}
