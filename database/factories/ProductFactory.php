<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Product;
use App\Infrastructure\Persistence\Models\ProductPrice;
use App\Infrastructure\Persistence\Models\SaleUnit;
use App\Traits\CodeGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    use CodeGenerator;

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = ucfirst($this->faker->word());
        $category = Category::inRandomOrder()->first();
        do {
            $code = $this->generateCode($category->name_en);
        } while (Product::where('code', $code)->exists());

        do {
            $originalCode = $this->faker->ean13();
        } while (Product::where('original_code', $originalCode)->exists());


        return [
            'category_id' => $category->id,
            'name_ar' => $name,
            'name_en' => $name,
            'original_code' => $originalCode,
            'code' => $code,
            'description' => $this->faker->sentence(20),
            'brand' => $this->faker->company(),
            'image' => $this->faker->imageUrl(),
            'origin' => $this->faker->country(),
            'units_per_carton' => $this->faker->numberBetween(1, 10),
            'sale_unit_id' => SaleUnit::get()->random()->id
        ];
    }

    // Inside ProductFactory class
    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            ProductPrice::factory()->create([
                'product_id' => $product->id,
                'type' => 'retail',
            ]);

            ProductPrice::factory()->create([
                'product_id' => $product->id,
                'type' => 'wholesale',
            ]);
        });
    }
}
