<?php

namespace Tests\Feature;

use App\Domain\Utils\ReturnedItemStatus;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\Purchase;
use App\Infrastructure\Persistence\Models\Sales;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PosClockworkLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_pos_lifecycle_works_like_clockwork(): void
    {
        $baseUrl = Constants::BASE_API_URL . '/v1';

        // 1. SETUP CATALOG & LOCATIONS
        $storeA = $this->createLocation(['name' => 'Store Alpha']);
        $storeB = $this->createLocation(['name' => 'Store Beta']);
        $supplier = $this->createSupplier(['name' => 'Global Supplier']);
        $product = $this->createProduct([], retailPrice: 100.0, wholesalePrice: 80.0);

        // 2. PURCHASE RECEIPT (Inbound Stock: 20 units into Store A)
        // Subtotal = 20 * 50 = 1000, 10% tax = 100, grand total = 1100
        $purchaseResponse = $this->postJson("{$baseUrl}/purchases", [
            'supplier_id' => $supplier->id,
            'store_id' => $storeA->id,
            'tax' => 10,
            'discount' => 0,
            'paper_total' => 1100.0,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 20,
                    'price' => 50.0,
                ],
            ],
        ]);
        $purchaseResponse->assertStatus(200);

        $purchase = Purchase::first();
        $this->assertNotNull($purchase);
        $batch = ProductBatch::first();
        $this->assertNotNull($batch);
        $this->assertEquals(20, $batch->remaining_quantity);

        // Verify Store A has 20, Store B has 0
        $this->assertEquals(20, DB::table('batch_locations')->where('location_id', $storeA->id)->value('remaining_quantity'));
        $this->assertNull(DB::table('batch_locations')->where('location_id', $storeB->id)->value('remaining_quantity'));

        // 3. STORE TRANSFER: Store A -> Store B (8 units)
        $transferResponse = $this->postJson("{$baseUrl}/stock/transfer", [
            'from_location_id' => $storeA->id,
            'to_location_id' => $storeB->id,
            'product_id' => $product->id,
            'quantity' => 8,
            'note' => 'Transfer stock from Alpha to Beta',
        ]);
        $transferResponse->assertStatus(200);

        // Verify intermediate stocks: Store A = 12, Store B = 8, Batch = 20
        $this->assertEquals(12, DB::table('batch_locations')->where('location_id', $storeA->id)->value('remaining_quantity'));
        $this->assertEquals(8, DB::table('batch_locations')->where('location_id', $storeB->id)->value('remaining_quantity'));
        $this->assertEquals(20, $batch->fresh()->remaining_quantity);

        // 4. RETAIL POS SALE AT STORE B (5 units, 10% discount)
        $saleBetaResponse = $this->postJson("{$baseUrl}/sales", [
            'customer_name' => 'Retail Customer at Beta',
            'store_id' => $storeB->id,
            'bill_type' => PriceType::RETAIL->value,
            'discount' => 10, // 10%
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ]);
        $saleBetaResponse->assertStatus(200);

        // Verify Store B stock drops from 8 to 3. Batch remaining drops from 20 to 15.
        $this->assertEquals(3, DB::table('batch_locations')->where('location_id', $storeB->id)->value('remaining_quantity'));
        $this->assertEquals(15, $batch->fresh()->remaining_quantity);

        $saleBeta = Sales::where('store_id', $storeB->id)->first();
        $this->assertNotNull($saleBeta);

        // 5. WHOLESALE POS SALE AT STORE A (10 units at wholesale price $80)
        $saleAlphaResponse = $this->postJson("{$baseUrl}/sales", [
            'customer_name' => 'Wholesale Merchant at Alpha',
            'store_id' => $storeA->id,
            'bill_type' => PriceType::WHOLESALE->value,
            'discount' => 0,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 10],
            ],
        ]);
        $saleAlphaResponse->assertStatus(200);

        // Verify Store A stock drops from 12 to 2. Batch remaining drops from 15 to 5.
        $this->assertEquals(2, DB::table('batch_locations')->where('location_id', $storeA->id)->value('remaining_quantity'));
        $this->assertEquals(5, $batch->fresh()->remaining_quantity);

        // 6. CUSTOMER RETURN AT STORE B (Customer returns 2 units)
        $returnBetaResponse = $this->postJson("{$baseUrl}/sales/returns", [
            'sales_id' => $saleBeta->id,
            'store_id' => $storeB->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $returnBetaResponse->assertStatus(200);

        // Verify Store B stock rises from 3 to 5. Batch remaining rises from 5 to 7.
        $this->assertEquals(5, DB::table('batch_locations')->where('location_id', $storeB->id)->value('remaining_quantity'));
        $this->assertEquals(7, $batch->fresh()->remaining_quantity);

        // 7. SUPPLIER PURCHASE RETURN FROM STORE A (Store A returns 2 units to Supplier)
        $purchaseReturnResponse = $this->post("/purchase-returns", [
            'purchase_id' => $purchase->id,
            'store_id' => $storeA->id,
            'reason' => 'Returning defective items to supplier',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $purchaseReturnResponse->assertSessionHasNoErrors();
        $purchaseReturnResponse->assertRedirect();

        // Verify Store A stock drops from 2 to 0. Batch remaining drops from 7 to 5.
        $this->assertEquals(0, DB::table('batch_locations')->where('location_id', $storeA->id)->value('remaining_quantity'));
        $this->assertEquals(5, $batch->fresh()->remaining_quantity);

        // 8. TOTAL MATHEMATICAL INVENTORY RECONCILIATION
        $storeAStock = (int) DB::table('batch_locations')->where('location_id', $storeA->id)->value('remaining_quantity');
        $storeBStock = (int) DB::table('batch_locations')->where('location_id', $storeB->id)->value('remaining_quantity');
        $batchStock = (int) $batch->fresh()->remaining_quantity;

        // In-store stocks MUST equal batch remaining stock
        $this->assertEquals(0, $storeAStock);
        $this->assertEquals(5, $storeBStock);
        $this->assertEquals($storeAStock + $storeBStock, $batchStock);

        // Sum of all stock movements for this batch MUST equal remaining stock:
        // +20 (entry) - 8 (transfer out) + 8 (transfer in) - 5 (sale) - 10 (sale) + 2 (customer return) - 2 (supplier return) = 5
        $netStockMovements = (int) StockMovement::where('product_batch_id', $batch->id)->sum('quantity');
        $this->assertEquals(5, $netStockMovements);
        $this->assertEquals($batchStock, $netStockMovements);

        // 9. OUT-OF-STOCK BARRIER ENFORCEMENT
        // Store A has 0 units left -> Selling 1 unit must fail
        $failSaleA = $this->postJson("{$baseUrl}/sales", [
            'customer_name' => 'Customer Attempting Empty Store',
            'store_id' => $storeA->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ]);
        $this->assertTrue(in_array($failSaleA->status(), [400, 422, 500]));

        // Store B has 5 units left -> Selling 6 units must fail
        $failSaleB = $this->postJson("{$baseUrl}/sales", [
            'customer_name' => 'Customer Requesting 6 when 5 available',
            'store_id' => $storeB->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 6],
            ],
        ]);
        $this->assertTrue(in_array($failSaleB->status(), [400, 422, 500]));

        // Stocks remained untouched after failed attempts
        $this->assertEquals(0, DB::table('batch_locations')->where('location_id', $storeA->id)->value('remaining_quantity'));
        $this->assertEquals(5, DB::table('batch_locations')->where('location_id', $storeB->id)->value('remaining_quantity'));
        $this->assertEquals(5, $batch->fresh()->remaining_quantity);
    }
}
