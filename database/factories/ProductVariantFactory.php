<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL']),
            'color' => fake()->safeColorName(),
            'color_code' => fake()->hexColor(),
            'sku' => strtoupper(Str::random(10)),
            'stock_quantity' => fake()->numberBetween(5, 50),
            'price_adjustment' => 0,
            'is_active' => true,
        ];
    }
}
