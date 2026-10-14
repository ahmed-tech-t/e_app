<?php

namespace Tests\Feature;

use App\Domain\Utils\ReturnedItemStatus;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Location;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\Purchase;
use App\Infrastructure\Persistence\Models\PurchaseReturn;
use App\Infrastructure\Persistence\Models\Sales;
use App\Infrastructure\Persistence\Models\SalesReturns;
use App\Infrastructure\Persistence\Models\SaleUnit;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\Models\Supplier;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;
use Tests\TestCase;

class LivewirePosUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_livewire_stock_transfer_wizard(): void
    {
        $storeA = $this->createLocation(['name' => 'Store Alpha']);
        $storeB = $this->createLocation(['name' => 'Store Beta']);
        $product = $this->createProduct([], retailPrice: 100.0);
        $this->createBatch($product, $storeA, quantity: 10);

        Volt::test('stock.stock-transfer')
            ->assertSee('Select Product')
            ->set('productId', $product->id)
            ->set('fromLocationId', $storeA->id)
            ->assertSet('availableQuantity', 10)
            ->set('toLocationId', $storeB->id)
            ->set('quantity', 4)
            ->call('goToPreview')
            ->assertSet('step', 2)
            ->call('goBack')
            ->assertSet('step', 1)
            ->call('goToPreview')
            ->assertSet('step', 2)
            ->call('transfer')
            ->assertRedirect(route('stock.index'));

        // Stock in database must be updated: Store A = 6, Store B = 4
        $this->assertEquals(
            6,
            DB::table('batch_locations')
                ->where('location_id', $storeA->id)
                ->value('remaining_quantity')
        );
        $this->assertEquals(
            4,
            DB::table('batch_locations')
                ->where('location_id', $storeB->id)
                ->value('remaining_quantity')
        );
    }

    public function test_livewire_stock_transfer_validation(): void
    {
        Volt::test('stock.stock-transfer')
            ->call('goToPreview')
            ->assertHasErrors(['productId', 'fromLocationId', 'toLocationId'])
            ->assertSet('step', 1);
    }

    public function test_livewire_stock_movements_filtering_and_reset(): void
    {
        $store = $this->createLocation(['name' => 'Main Warehouse']);
        $product = $this->createProduct();
        $batch = $this->createBatch($product, $store, quantity: 20);

        StockMovement::create([
            'product_batch_id' => $batch->id,
            'location_id' => $store->id,
            'quantity' => 20,
            'type' => StockMovementType::ENTRY->value,
            'bill_number' => 'ENTRY-TEST-1',
        ]);

        StockMovement::create([
            'product_batch_id' => $batch->id,
            'location_id' => $store->id,
            'quantity' => -5,
            'type' => StockMovementType::SALE->value,
            'bill_number' => 'SAL-TEST-1',
        ]);

        Volt::test('stock.stock-movements')
            ->assertSee('ENTRY-TEST-1')
            ->assertSee('SAL-TEST-1')
            ->set('type', 'sale')
            ->assertSee('SAL-TEST-1')
            ->assertDontSee('ENTRY-TEST-1')
            ->set('type', 'entry')
            ->assertSee('ENTRY-TEST-1')
            ->assertDontSee('SAL-TEST-1')
            ->call('resetFilters')
            ->assertSet('type', '')
            ->assertSet('search', '')
            ->assertSet('locationId', '')
            ->assertSet('dateFrom', '');
    }

    public function test_livewire_sales_search_component(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct();
        $this->createBatch($product, $store, quantity: 20);

        $salesApiUrl = Constants::BASE_API_URL . '/v1/sales';

        $this->postJson($salesApiUrl, [
            'customer_name' => 'Tariq Ziyad',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $this->postJson($salesApiUrl, [
            'customer_name' => 'Layla Majnun',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        Volt::test('search.sale-search')
            ->assertSee('Tariq Ziyad')
            ->assertSee('Layla Majnun')
            ->set('search', 'Tariq')
            ->assertSee('Tariq Ziyad')
            ->assertDontSee('Layla Majnun')
            ->set('search', 'Layla')
            ->assertSee('Layla Majnun')
            ->assertDontSee('Tariq Ziyad');
    }

    public function test_livewire_purchases_search_component(): void
    {
        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        $purchaseApiUrl = Constants::BASE_API_URL . '/v1/purchases';

        $this->postJson($purchaseApiUrl, [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
            'tax' => 0,
            'discount' => 0,
            'paper_total' => 100.0,
            'items' => [['product_id' => $product->id, 'quantity' => 2, 'price' => 50.0]],
        ]);

        $purchase = Purchase::first();

        Volt::test('search.purchase-search')
            ->assertSee($purchase->code)
            ->set('search', 'NONEXISTENT_CODE')
            ->assertDontSee($purchase->code)
            ->set('search', $purchase->code)
            ->assertSee($purchase->code);
    }

    public function test_livewire_sales_returns_search_component(): void
    {
        $store = $this->createLocation();
        $product = $this->createProduct([], retailPrice: 100.0);
        $this->createBatch($product, $store, quantity: 10);

        $this->postJson(Constants::BASE_API_URL . '/v1/sales', [
            'customer_name' => 'Return Customer',
            'store_id' => $store->id,
            'bill_type' => PriceType::RETAIL->value,
            'items' => [['product_id' => $product->id, 'quantity' => 2]],
        ]);

        $sale = Sales::first();

        $this->postJson(Constants::BASE_API_URL . '/v1/sales/returns', [
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

        $salesReturn = SalesReturns::first();

        Volt::test('search.sales-return-search')
            ->assertSee($salesReturn->code)
            ->set('search', 'NO_MATCH')
            ->assertDontSee($salesReturn->code)
            ->set('search', $salesReturn->code)
            ->assertSee($salesReturn->code);
    }

    public function test_livewire_purchase_returns_search_component(): void
    {
        $supplier = $this->createSupplier();
        $store = $this->createLocation();
        $product = $this->createProduct();

        $this->postJson(Constants::BASE_API_URL . '/v1/purchases', [
            'supplier_id' => $supplier->id,
            'store_id' => $store->id,
            'tax' => 0,
            'discount' => 0,
            'paper_total' => 200.0,
            'items' => [['product_id' => $product->id, 'quantity' => 4, 'price' => 50.0]],
        ]);

        $purchase = Purchase::first();

        $this->post('/purchase-returns', [
            'purchase_id' => $purchase->id,
            'store_id' => $store->id,
            'reason' => 'Quality defect reason',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'restock_status' => ReturnedItemStatus::RESEALABLE->value,
                ],
            ],
        ]);

        $purchaseReturn = PurchaseReturn::first();

        Volt::test('search.purchase-return-search')
            ->assertSee($purchaseReturn->code)
            ->set('search', 'NO_MATCH')
            ->assertDontSee($purchaseReturn->code)
            ->set('search', $purchaseReturn->code)
            ->assertSee($purchaseReturn->code);
    }

    public function test_livewire_product_search_component(): void
    {
        $this->createProduct(['name_en' => 'Espresso Roast', 'name_ar' => 'اسبريسو فاخر']);
        $this->createProduct(['name_en' => 'Jasmine Green Tea', 'name_ar' => 'شاي اخضر ياسمين']);

        Volt::test('search.product-search')
            ->assertSee('اسبريسو فاخر')
            ->assertSee('شاي اخضر ياسمين')
            ->set('search', 'Espresso')
            ->assertSee('اسبريسو فاخر')
            ->assertDontSee('شاي اخضر ياسمين')
            ->set('search', 'Jasmine')
            ->assertSee('شاي اخضر ياسمين')
            ->assertDontSee('اسبريسو فاخر');
    }

    public function test_livewire_secondary_entities_search_components(): void
    {
        // 1. Supplier search
        $this->createSupplier(['name' => 'Acme Supplies Co']);
        $this->createSupplier(['name' => 'Zeta Logistics']);

        Volt::test('search.supplier-search')
            ->assertSee('Acme Supplies Co')
            ->assertSee('Zeta Logistics')
            ->set('search', 'Acme')
            ->assertSee('Acme Supplies Co')
            ->assertDontSee('Zeta Logistics');

        // 2. Category search
        $this->createCategory(['name_en' => 'Beverages']);
        $this->createCategory(['name_en' => 'Electronics']);

        Volt::test('search.category-search')
            ->assertSee('Beverages')
            ->assertSee('Electronics')
            ->set('search', 'Beverages')
            ->assertSee('Beverages')
            ->assertDontSee('Electronics');

        // 3. Location search
        $this->createLocation(['name' => 'Airport Terminal']);
        $this->createLocation(['name' => 'Harbor Warehouse']);

        Volt::test('search.location-search')
            ->assertSee('Airport Terminal')
            ->assertSee('Harbor Warehouse')
            ->set('search', 'Airport')
            ->assertSee('Airport Terminal')
            ->assertDontSee('Harbor Warehouse');

        // 4. Sale Unit search
        $this->createSaleUnit(['name_en' => 'Kilogram']);
        $this->createSaleUnit(['name_en' => 'Piece']);

        Volt::test('search.sale-unit-search')
            ->assertSee('Kilogram')
            ->assertSee('Piece')
            ->set('search', 'Kilogram')
            ->assertSee('Kilogram')
            ->assertDontSee('Piece');
    }
}
