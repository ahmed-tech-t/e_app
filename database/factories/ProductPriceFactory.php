<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class ProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProductPrice::class;
    public function definition(): array
    {
        return [
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'type' => null,
            'product_id' => null,
            'valid_from' => now(),
        ];
    }
}
