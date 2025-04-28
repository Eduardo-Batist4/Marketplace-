<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    public function definition(): array
    {
        $discount_percentage = fake()->numberBetween(1, 12) * 5;

        return [
            'code' => strtoupper(fake()->lexify('????') . sprintf('%02d', $discount_percentage)),
            'discount_percentage' => $discount_percentage,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d')
        ];
    }
}