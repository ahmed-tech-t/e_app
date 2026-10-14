<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebPosTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_web_pos_views_render_successfully(): void
    {
        $store = $this->createLocation();
        $supplier = $this->createSupplier();
        $category = $this->createCategory();
        $saleUnit = $this->createSaleUnit();
        $product = $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
        ]);
        $this->createBatch($product, $store, quantity: 10);

        // 1. Dashboard
        $this->get('/')->assertStatus(200);

        // 2. Sales views
        $this->get('/sales')->assertStatus(200);
        $this->get('/sales/create')->assertStatus(200);

        // 3. Purchases views
        $this->get('/purchases')->assertStatus(200);
        $this->get('/purchases/create')->assertStatus(200);

        // 4. Stock management views
        $this->get('/stock')->assertStatus(200);
        $this->get('/stock/transfer')->assertStatus(200);

        // 5. Returns views
        $this->get('/sales-returns')->assertStatus(200);
        $this->get('/purchase-returns')->assertStatus(200);

        // 6. Master data views
        $this->get('/products')->assertStatus(200);
        $this->get('/products/create')->assertStatus(200);
        $this->get("/products/{$product->id}")->assertStatus(200);
        $this->get("/products/{$product->id}/edit")->assertStatus(200);

        $this->get('/suppliers')->assertStatus(200);
        $this->get('/suppliers/create')->assertStatus(200);

        $this->get('/locations')->assertStatus(200);
        $this->get('/locations/create')->assertStatus(200);

        $this->get('/categories')->assertStatus(200);
        $this->get('/categories/create')->assertStatus(200);

        $this->get('/sale-units')->assertStatus(200);
        $this->get('/sale-units/create')->assertStatus(200);

        // 7. Product export pages
        $this->get('/products/export')->assertStatus(200);
        $this->get('/products/export-template')->assertStatus(200);

        // 8. Locale switcher
        $this->post('/locale/ar')->assertSessionHas('locale', 'ar');
        $this->post('/locale/en')->assertSessionHas('locale', 'en');
    }

    public function test_web_pos_transaction_detail_views_and_form_submissions(): void
    {
        $store1 = $this->createLocation(['name' => 'Store 1']);
        $store2 = $this->createLocation(['name' => 'Store 2']);
        $supplier = $this->createSupplier();
        $product = $this->createProduct([], retailPrice: 100.0);

        // 1. Web Purchase Submission & Show View
        $purchaseResponse = $this->post('/purchases', [
            'supplier_id' => $supplier->id,
            'store_id' => $store1->id,
            'tax' => 10,
            'discount' => 0,
            'paper_total' => 1100.0,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 10, 'price' => 100.0],
            ],
        ]);
        $purchaseResponse->assertRedirect(route('purchases.index'));
        $purchaseResponse->assertSessionHas('success');

        $purchase = \App\Infrastructure\Persistence\Models\Purchase::first();
        $this->get("/purchases/{$purchase->id}")->assertStatus(200);
        $this->get("/purchases/{$purchase->id}/return")->assertStatus(200);

        // 2. Web Stock Transfer Submission
        $transferResponse = $this->post('/stock/transfer', [
            'from_location_id' => $store1->id,
            'to_location_id' => $store2->id,
            'product_id' => $product->id,
            'quantity' => 4,
            'note' => 'Web stock transfer',
        ]);
        $transferResponse->assertRedirect(route('stock.index'));
        $transferResponse->assertSessionHas('success');

        // 3. Web Sale Submission & Show View
        $saleResponse = $this->post('/sales', [
            'customer_name' => 'Web Customer',
            'store_id' => $store2->id,
            'bill_type' => \App\Infrastructure\Persistence\utils\PriceType::RETAIL->value,
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ]);
        $saleResponse->assertRedirect(route('sales.index'));
        $saleResponse->assertSessionHas('success');

        $sale = \App\Infrastructure\Persistence\Models\Sales::first();
        $this->get("/sales/{$sale->id}")->assertStatus(200);
        $this->get("/sales/{$sale->id}/return")->assertStatus(200);

        // 4. Web Sales Return Submission & Show View
        $salesReturnResponse = $this->post('/sales-returns', [
            'sales_id' => $sale->id,
            'store_id' => $store2->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'restock_status' => \App\Domain\Utils\ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $salesReturnResponse->assertRedirect(route('sales-returns.index'));
        $salesReturnResponse->assertSessionHas('success');

        $salesReturn = \App\Infrastructure\Persistence\Models\SalesReturns::first();
        $this->get("/sales-returns/{$salesReturn->id}")->assertStatus(200);

        // 5. Web Purchase Return Submission & Show View
        $purchaseReturnResponse = $this->post('/purchase-returns', [
            'purchase_id' => $purchase->id,
            'store_id' => $store1->id,
            'reason' => 'Defective from supplier',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'restock_status' => \App\Domain\Utils\ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);
        $purchaseReturnResponse->assertRedirect(route('purchase-returns.index'));
        $purchaseReturnResponse->assertSessionHas('success');

        $purchaseReturn = \App\Infrastructure\Persistence\Models\PurchaseReturn::first();
        $this->get("/purchase-returns/{$purchaseReturn->id}")->assertStatus(200);
    }
}

