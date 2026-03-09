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
        Product::factory(2)->create();
    }
}
