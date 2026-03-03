<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = StockMovement::class;
    public function definition(): array
    {
        return [
            'product_batch_id' => null,
            'location_id' => null,
            'quantity' => null,
            'type' => null,
            'bill_number' => null
        ];
    }
}
