<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = $this->faker->ean13();
        return [
            'code' => substr($code, 0, 5),
            'name' => ucfirst($this->faker->word()),
            'address' => $this->faker->streetAddress(),
        ];
    }
}
