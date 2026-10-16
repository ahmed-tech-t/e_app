<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (\App\Infrastructure\Persistence\Models\Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }
        if (\App\Infrastructure\Persistence\Models\SaleUnit::count() === 0) {
            $this->call(SaleUnitSeeder::class);
        }

        Product::factory(100)->create();
    }
}
