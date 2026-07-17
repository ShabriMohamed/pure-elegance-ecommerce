<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'sku' => strtoupper(Str::random(10)),
            'description' => fake()->sentence(),
            'short_description' => fake()->sentence(6),
            'price' => fake()->randomFloat(2, 1000, 20000),
            'sale_price' => null,
            'category_id' => Category::factory(),
            'gender' => fake()->randomElement(['men', 'women', 'unisex']),
            'brand' => fake()->company(),
            'stock_quantity' => fake()->numberBetween(5, 100),
            'is_active' => true,
            'is_featured' => false,
            'is_new_arrival' => false,
            'view_count' => 0,
        ];
    }

    public function onSale(float $salePrice = null): static
    {
        return $this->state(fn (array $attrs) => [
            'sale_price' => $salePrice ?? round($attrs['price'] * 0.8, 2),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock_quantity' => 0]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }
}
