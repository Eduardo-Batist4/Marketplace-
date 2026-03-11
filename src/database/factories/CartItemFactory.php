<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cart_id' => random_int(2,4),
            'product_id' => random_int(1,10),
            'quantity' => 1,
            'unit_price' => random_int(100, 1000),
        ];
    }
}
