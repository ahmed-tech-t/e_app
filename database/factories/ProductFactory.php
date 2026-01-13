<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fullEan = $this->faker->ean13();
        return [
            'name' => ucfirst($this->faker->word()),
            'code' => substr($fullEan, 0, 5),
            'price' => $this->faker->randomFloat(2, 245435, 6000000),
            'origin' => $this->faker->country(),
            'description' => $this->faker->sentence(20),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
