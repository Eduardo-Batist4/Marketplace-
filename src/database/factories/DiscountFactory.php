<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'discount_percentage' => fake()->numberBetween(5, 50),
            'description' => fake()->sentence(),
            'start_date' => now(),
            'end_date' => now()->addDays(7),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
