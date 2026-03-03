<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Traits\CodeGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductBatchSeeder extends Seeder
{
    use CodeGenerator;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 500 total / 10 per group = 50 iterations
        for ($i = 1; $i <= 50; $i++) {

            $products = Product::inRandomOrder()->limit(10)->get();

            // Generate the code once for this group
            $currentBatchCode = $this->generateCode('BAT');

            ProductBatch::factory(10)
                ->state(new Sequence(
                    fn($sequence) => [
                        'product_id' => $products[$sequence->index]->id
                    ]
                ))
                ->create([
                    'batch_code' => $currentBatchCode,
                ]);
        }
    }




}
