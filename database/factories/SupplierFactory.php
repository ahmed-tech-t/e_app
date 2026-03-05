<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Supplier::class;
    public function definition(): array
    {
        $code = $this->faker->ean13();
        return [
            'code' => substr($code, 0, 5),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
        ];
    }
}
