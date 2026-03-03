<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Location;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\ProductBatch;
use App\Infrastructure\Persistence\Models\StockMovement;
use App\Infrastructure\Persistence\utils\StockMovementType;
use App\Traits\CodeGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class ProductBatchFactory extends Factory
{
    use CodeGenerator;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProductBatch::class;
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 100);


        return [
            'product_id' => null,
            'batch_code' => null,
            'initial_quantity' => $quantity,
            'remaining_quantity' => $quantity,
            'cost_price' => $this->faker->randomFloat(2, 1, 1000)
        ];
    }

    public function configure()
    {


        return $this->afterCreating(function (ProductBatch $productBatch) {

            $location = Location::inRandomOrder()->first();
            $productBatch->locations()->attach($location, [
                'remaining_quantity' => $productBatch->initial_quantity
            ]);

            StockMovement::factory()->create([
                'product_batch_id' => $productBatch->id,
                'location_id' => $location->id,
                'quantity' => $productBatch->initial_quantity,
                'type' => StockMovementType::ENTRY
            ]);
        });
    }
}
