<?php

namespace Tests\Feature;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Location;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\SaleUnit;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Utils\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    private string $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = Constants::BASE_API_URL.'/v1';
    }

    public function test_category_crud_operations(): void
    {
        // 1. Create Category
        $createPayload = [
            'name_ar' => 'أدوات منزلية',
            'name_en' => 'Housewares',
            'description' => 'Category Description',
        ];

        $res = $this->postJson("{$this->baseUrl}/categories", $createPayload);
        $res->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name_en' => 'Housewares']);

        $category = Category::where('name_en', 'Housewares')->first();

        // 2. Get Category List & Show
        $this->getJson("{$this->baseUrl}/categories")->assertStatus(200);
        $this->getJson("{$this->baseUrl}/categories/{$category->id}")->assertStatus(200);

        // 3. Update Category
        $updatePayload = [
            'name_ar' => 'أدوات منزلية حديثة',
            'name_en' => 'Modern Housewares',
            'description' => 'Updated Description',
        ];
        $this->putJson("{$this->baseUrl}/categories/{$category->id}", $updatePayload)->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name_en' => 'Modern Housewares']);

        // 4. Delete Category (Soft delete)
        $this->deleteJson("{$this->baseUrl}/categories/{$category->id}")->assertStatus(200);
        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_sale_unit_crud_operations(): void
    {
        // 1. Create Sale Unit
        $createPayload = [
            'name_ar' => 'علبة',
            'name_en' => 'Box',
        ];

        $res = $this->postJson("{$this->baseUrl}/sale-units", $createPayload);
        $res->assertStatus(200);
        $this->assertDatabaseHas('sale_units', ['name_en' => 'Box']);

        $unit = SaleUnit::where('name_en', 'Box')->first();

        // 2. Get Sale Units
        $this->getJson("{$this->baseUrl}/sale-units")->assertStatus(200);
        $this->getJson("{$this->baseUrl}/sale-units/{$unit->id}")->assertStatus(200);

        // 3. Update Sale Unit
        $this->putJson("{$this->baseUrl}/sale-units/{$unit->id}", [
            'name_ar' => 'علبة كبيرة',
            'name_en' => 'Large Box',
        ])->assertStatus(200);
        $this->assertDatabaseHas('sale_units', ['name_en' => 'Large Box']);

        // 4. Delete Sale Unit
        $this->deleteJson("{$this->baseUrl}/sale-units/{$unit->id}")->assertStatus(200);
        $this->assertSoftDeleted('sale_units', ['id' => $unit->id]);
    }

    public function test_location_crud_operations(): void
    {
        // 1. Create Location
        $createPayload = [
            'name' => 'Downtown Branch',
            'address' => 'Downtown Street',
            'phone' => '01099887766',
            'type' => 'store',
        ];

        $res = $this->postJson("{$this->baseUrl}/locations", $createPayload);
        $res->assertStatus(200);
        $this->assertDatabaseHas('locations', ['name' => 'Downtown Branch']);

        $location = Location::where('name', 'Downtown Branch')->first();

        // 2. Read
        $this->getJson("{$this->baseUrl}/locations")->assertStatus(200);
        $this->getJson("{$this->baseUrl}/locations/{$location->id}")->assertStatus(200);

        // 3. Update
        $this->putJson("{$this->baseUrl}/locations/{$location->id}", [
            'name' => 'Downtown Main Branch',
            'address' => 'New Address',
            'phone' => '01099887766',
            'type' => 'store',
        ])->assertStatus(200);
        $this->assertDatabaseHas('locations', ['name' => 'Downtown Main Branch']);

        // 4. Delete
        $this->deleteJson("{$this->baseUrl}/locations/{$location->id}")->assertStatus(200);
        $this->assertSoftDeleted('locations', ['id' => $location->id]);
    }

    public function test_product_crud_and_price_history(): void
    {
        $category = $this->createCategory();
        $saleUnit = $this->createSaleUnit();

        // 1. Create Product with initial prices
        $createPayload = [
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'original_code' => 'BAR-998877',
            'name_ar' => 'شاي فاخر',
            'name_en' => 'Premium Tea',
            'brand' => 'TeaCo',
            'origin' => 'Imported',
            'units_per_carton' => 24,
            'size' => '250g',
            'retail_price' => 45.0,
            'wholesale_price' => 38.0,
        ];

        $res = $this->postJson("{$this->baseUrl}/products", $createPayload);
        $res->assertStatus(200);
        $this->assertDatabaseHas('products', ['original_code' => 'BAR-998877', 'name_en' => 'Premium Tea']);

        $product = Product::where('original_code', 'BAR-998877')->first();
        $this->assertNotNull($product);

        // Verify initial prices created
        $this->assertDatabaseHas('product_prices', [
            'product_id' => $product->id,
            'price' => 45.0,
            'type' => PriceType::RETAIL->value,
        ]);
        $this->assertDatabaseHas('product_prices', [
            'product_id' => $product->id,
            'price' => 38.0,
            'type' => PriceType::WHOLESALE->value,
        ]);

        // 2. Show & Index
        $this->getJson("{$this->baseUrl}/products/{$product->id}")->assertStatus(200);
        $this->getJson("{$this->baseUrl}/products")->assertStatus(200);

        // 3. Update Product Price via Price API
        $priceUpdatePayload = [
            'price' => 50.0,
            'type' => 'retail',
        ];
        $this->postJson("{$this->baseUrl}/products/{$product->id}/price", $priceUpdatePayload)->assertStatus(200);

        // Old retail price should be invalidated (valid_to set) and new retail price active
        $this->assertDatabaseHas('product_prices', [
            'product_id' => $product->id,
            'price' => 50.0,
            'type' => PriceType::RETAIL->value,
            'valid_to' => null,
        ]);

        // 4. Price History Check
        $historyRes = $this->getJson("{$this->baseUrl}/products/{$product->id}/price?type=retail");
        $historyRes->assertStatus(200);
        $historyData = $historyRes->json('data');
        $this->assertCount(2, $historyData); // Initial 45.0 + updated 50.0

        // 5. Delete Product
        $this->deleteJson("{$this->baseUrl}/products/{$product->id}")->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_product_search_by_code_and_barcode_scanner(): void
    {
        $category = $this->createCategory();
        $saleUnit = $this->createSaleUnit();

        $prod1 = $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'original_code' => 'BAR-111111',
            'name_en' => 'Apple Juice',
            'name_ar' => 'عصير تفاح',
        ]);

        $prod2 = $this->createProduct([
            'category_id' => $category->id,
            'sale_unit_id' => $saleUnit->id,
            'original_code' => 'BAR-222222',
            'name_en' => 'Orange Juice',
            'name_ar' => 'عصير برتقال',
        ]);

        // 1. Search by exact barcode (original_code)
        $res = $this->getJson("{$this->baseUrl}/products/search?original_code=BAR-111111");
        $res->assertStatus(200);
        $items = $res->json('data.items');
        $this->assertCount(1, $items);
        $this->assertEquals($prod1->id, $items[0]['id']);

        // 2. Search by internal system code
        $resCode = $this->getJson("{$this->baseUrl}/products/search?code={$prod2->code}");
        $resCode->assertStatus(200);
        $itemsCode = $resCode->json('data.items');
        $this->assertCount(1, $itemsCode);
        $this->assertEquals($prod2->id, $itemsCode[0]['id']);

        // 3. Search by English name
        $resName = $this->getJson("{$this->baseUrl}/products/search?name_en=Apple");
        $resName->assertStatus(200);
        $itemsName = $resName->json('data.items');
        $this->assertCount(1, $itemsName);
        $this->assertEquals($prod1->id, $itemsName[0]['id']);
    }

    public function test_product_location_inventory_and_stock_lookup(): void
    {
        $storeA = $this->createLocation();
        $storeB = $this->createLocation();
        $product = $this->createProduct();

        $this->createBatch($product, $storeA, quantity: 15);

        // 1. Query products available at Store A
        $resA = $this->getJson("{$this->baseUrl}/products/locations/{$storeA->id}");
        $resA->assertStatus(200);
        $itemsA = $resA->json('data.items');
        $this->assertCount(1, $itemsA);
        $this->assertEquals($product->id, $itemsA[0]['id']);
        $this->assertEquals(15, $itemsA[0]['quantity']);

        // 2. Query specific product at Store A
        $resSingleA = $this->getJson("{$this->baseUrl}/products/{$product->id}/locations/{$storeA->id}");
        $resSingleA->assertStatus(200);
        $this->assertEquals(15, $resSingleA->json('data.quantity'));

        // 3. Store B has no stock for this product
        $resB = $this->getJson("{$this->baseUrl}/products/locations/{$storeB->id}");
        $resB->assertStatus(200);
        $this->assertEmpty($resB->json('data.items'));
        $this->assertEquals(0, $resB->json('data.meta.total'));
    }

    public function test_product_excel_export_and_template(): void
    {
        \Maatwebsite\Excel\Facades\Excel::fake();

        // 1. Export template
        $this->get('/products/export-template')->assertStatus(200);
        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('products-template.xlsx');

        // 2. Export products with columns
        $this->post('/products/export', [
            'columns' => ['name_en', 'name_ar', 'retail_price'],
        ])->assertStatus(200);
        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('products.xlsx');
    }
}
