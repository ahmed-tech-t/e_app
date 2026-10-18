<?php

namespace Tests\Feature;

use App\Application\DTOs\TransferProductDto;
use App\Application\Services\StockService;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    private string $transferApiUrl;

    private string $searchApiUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transferApiUrl = Constants::BASE_API_URL.'/v1/stock/transfer';
        $this->searchApiUrl = Constants::BASE_API_URL.'/v1/stock/search';
    }

    public function test_transfers_product_between_locations_successfully(): void
    {
        $storeA = $this->createLocation(['name' => 'Store A']);
        $storeB = $this->createLocation(['name' => 'Store B']);
        $product = $this->createProduct();
        $batch = $this->createBatch($product, $storeA, quantity: 20);

        $payload = [
            'product_id' => $product->id,
            'from_location_id' => $storeA->id,
            'to_location_id' => $storeB->id,
            'quantity' => 7,
        ];

        $response = $this->postJson($this->transferApiUrl, $payload);
        $response->assertStatus(200);

        // Overall batch remaining quantity should remain unchanged (20)
        $this->assertEquals(20, $batch->fresh()->remaining_quantity);

        // Store A stock reduced by 7 (20 - 7 = 13)
        $this->assertEquals(
            13,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $storeA->id)
                ->value('remaining_quantity')
        );

        // Store B stock increased by 7 (0 + 7 = 7)
        $this->assertEquals(
            7,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $storeB->id)
                ->value('remaining_quantity')
        );

        // Verify audit records: TRANSFER_OUT and TRANSFER_IN
        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch->id,
            'location_id' => $storeA->id,
            'quantity' => -7,
            'type' => StockMovementType::TRANSFER_OUT->value,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_batch_id' => $batch->id,
            'location_id' => $storeB->id,
            'quantity' => 7,
            'type' => StockMovementType::TRANSFER_IN->value,
        ]);
    }

    public function test_transfer_fails_when_quantity_exceeds_available_in_source_location(): void
    {
        $storeA = $this->createLocation();
        $storeB = $this->createLocation();
        $product = $this->createProduct();
        $this->createBatch($product, $storeA, quantity: 5);

        $payload = [
            'product_id' => $product->id,
            'from_location_id' => $storeA->id,
            'to_location_id' => $storeB->id,
            'quantity' => 10, // Requesting 10 when only 5 exist in store A
        ];

        $response = $this->postJson($this->transferApiUrl, $payload);
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));

        // No transfer movements recorded
        $this->assertDatabaseMissing('stock_movements', [
            'type' => StockMovementType::TRANSFER_OUT->value,
        ]);
    }

    public function test_transfer_fails_when_to_location_is_same_as_from_location(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct();
        $this->createBatch($product, $store, quantity: 10);

        $payload = [
            'product_id' => $product->id,
            'from_location_id' => $store->id,
            'to_location_id' => $store->id, // Same location
            'quantity' => 2,
        ];

        $response = $this->postJson($this->transferApiUrl, $payload);
        $response->assertStatus(422);
    }

    public function test_traces_stock_movement_audit_trail_and_search(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct();
        $batch = $this->createBatch($product, $store, quantity: 15);

        $response = $this->getJson($this->searchApiUrl.'?product_id='.$product->id);
        $response->assertStatus(200);

        $items = $response->json('data.items') ?? $response->json('data');
        $this->assertNotEmpty($items);
        $this->assertEquals(StockMovementType::ENTRY->value, $items[0]['type']);
    }

    public function test_direct_stock_service_transfer(): void
    {
        $stockService = app(StockService::class);
        $storeA = $this->createLocation();
        $storeB = $this->createLocation();
        $product = $this->createProduct();
        $batch = $this->createBatch($product, $storeA, quantity: 12);

        $dto = new TransferProductDto(
            productId: $product->id,
            fromLocationId: $storeA->id,
            toLocationId: $storeB->id,
            quantity: 4
        );

        $result = $stockService->transferProduct($dto);
        $this->assertTrue($result);

        $this->assertEquals(
            8,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $storeA->id)
                ->value('remaining_quantity')
        );
        $this->assertEquals(
            4,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch->id)
                ->where('location_id', $storeB->id)
                ->value('remaining_quantity')
        );
    }

    public function test_transfers_product_across_multiple_batches_correctly(): void
    {
        $storeA = $this->createLocation(['name' => 'Store Alpha']);
        $storeB = $this->createLocation(['name' => 'Store Beta']);
        $product = $this->createProduct();

        // Store A has Batch 1 (5 units, older) and Batch 2 (10 units, newer)
        $batch1 = $this->createBatch($product, $storeA, quantity: 5);
        $batch1->created_at = now()->subMinutes(15);
        $batch1->save();

        $batch2 = $this->createBatch($product, $storeA, quantity: 10);
        $batch2->created_at = now()->subMinutes(5);
        $batch2->save();

        // Transfer 12 units from Store A to Store B (depletes 5 from Batch 1, 7 from Batch 2)
        $payload = [
            'product_id' => $product->id,
            'from_location_id' => $storeA->id,
            'to_location_id' => $storeB->id,
            'quantity' => 12,
        ];

        $response = $this->postJson($this->transferApiUrl, $payload);
        $response->assertStatus(200);

        // Store A balances: Batch 1 has 0, Batch 2 has 3
        $this->assertEquals(
            0,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch1->id)
                ->where('location_id', $storeA->id)
                ->value('remaining_quantity')
        );
        $this->assertEquals(
            3,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch2->id)
                ->where('location_id', $storeA->id)
                ->value('remaining_quantity')
        );

        // Store B balances: Batch 1 has 5, Batch 2 has 7
        $this->assertEquals(
            5,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch1->id)
                ->where('location_id', $storeB->id)
                ->value('remaining_quantity')
        );
        $this->assertEquals(
            7,
            DB::table('batch_locations')
                ->where('product_batch_id', $batch2->id)
                ->where('location_id', $storeB->id)
                ->value('remaining_quantity')
        );
    }
}
