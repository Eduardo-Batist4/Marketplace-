<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {
        return [
            'user_id' => random_int(2,4),
            'street' => fake()->streetAddress(),
            'number' => random_int(100, 1000),
            'zip' => fake()->postcode(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country()
        ];
    }
}
