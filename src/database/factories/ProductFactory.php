<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 100, 1000),
            'stock' => fake()->numberBetween(1,5),
            'category_id' => Category::factory(),
            'image_path' => fake()->imageUrl(640, 480),
            'description' => fake()->text(200),
        ];
    }
}
