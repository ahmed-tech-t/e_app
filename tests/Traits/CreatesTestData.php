<?php

namespace Tests\Traits;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Location;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\ProductPrice;
use App\Infrastructure\Persistence\Models\SaleUnit;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\Models\Supplier;
use App\Infrastructure\Persistence\Models\User;
use App\Infrastructure\Persistence\utils\PriceType;
use App\Infrastructure\Persistence\utils\StockMovementType;
use Illuminate\Support\Facades\Hash;

trait CreatesTestData
{
    public function createUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'name' => 'Test User '.uniqid(),
            'email' => 'user_'.uniqid().'@example.com',
            'password' => Hash::make('password'),
        ], $overrides));
    }

    public function createCategory(array $overrides = []): Category
    {
        return Category::create(array_merge([
            'name_ar' => 'تصنيف تجريبي',
            'name_en' => 'Test Category '.uniqid(),
            'code' => 'CAT-'.strtoupper(uniqid()),
            'description' => 'Test Category Description',
        ], $overrides));
    }

    public function createSaleUnit(array $overrides = []): SaleUnit
    {
        return SaleUnit::create(array_merge([
            'name_ar' => 'قطعة '.uniqid(),
            'name_en' => 'Piece '.uniqid(),
            'code' => 'PC-'.strtoupper(uniqid()),
        ], $overrides));
    }

    public function createLocation(array $overrides = []): Location
    {
        return Location::create(array_merge([
            'name' => 'Main Store '.uniqid(),
            'code' => 'LOC-'.strtoupper(uniqid()),
            'address' => 'Store Address',
            'phone' => '010'.rand(10000000, 99999999),
            'type' => 'store',
        ], $overrides));
    }

    public function createSupplier(array $overrides = []): Supplier
    {
        return Supplier::create(array_merge([
            'name' => 'Supplier '.uniqid(),
            'code' => 'SUP-'.strtoupper(uniqid()),
            'contact_person' => 'John Doe',
            'phone' => '010'.rand(10000000, 99999999),
            'email' => 'supplier_'.uniqid().'@example.com',
            'address' => 'Supplier Address',
        ], $overrides));
    }

    public function createProduct(array $overrides = [], float $retailPrice = 100.0, float $wholesalePrice = 80.0): Product
    {
        $category = isset($overrides['category_id']) ? null : $this->createCategory();
        $saleUnit = isset($overrides['sale_unit_id']) ? null : $this->createSaleUnit();

        $product = Product::create(array_merge([
            'category_id' => $category?->id,
            'sale_unit_id' => $saleUnit?->id,
            'name_ar' => 'منتج تجريبي',
            'name_en' => 'Test Product '.uniqid(),
            'code' => 'PRD-'.strtoupper(uniqid()),
            'original_code' => 'BAR-'.strtoupper(uniqid()),
            'description' => 'Product description',
            'brand' => 'Brand Test',
            'origin' => 'Local',
            'units_per_carton' => 1,
            'size' => 'M',
        ], $overrides));

        ProductPrice::create([
            'product_id' => $product->id,
            'price' => $retailPrice,
            'type' => PriceType::RETAIL->value,
            'valid_from' => now(),
            'status' => true,
        ]);

        ProductPrice::create([
            'product_id' => $product->id,
            'price' => $wholesalePrice,
            'type' => PriceType::WHOLESALE->value,
            'valid_from' => now(),
            'status' => true,
        ]);

        return $product;
    }

    public function createBatch(Product $product, Location $location, int $quantity = 10, float $costPrice = 50.0): ProductBatch
    {
        $batch = ProductBatch::create([
            'product_id' => $product->id,
            'batch_code' => 'BATCH-'.strtoupper(uniqid()),
            'initial_quantity' => 0,
            'remaining_quantity' => 0,
            'cost_price' => $costPrice,
        ]);

        StockMovement::create([
            'product_batch_id' => $batch->id,
            'location_id' => $location->id,
            'quantity' => $quantity,
            'type' => StockMovementType::ENTRY->value,
            'bill_number' => 'INIT-'.strtoupper(uniqid()),
        ]);

        return $batch->fresh();
    }
}
