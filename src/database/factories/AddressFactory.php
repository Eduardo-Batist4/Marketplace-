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

    static function randomId()
    {
        $count = 1;

        if ($count == 4) {
            $count = 2;
        }
        $count++;
        
        return $count;
    } 
    
    public function definition(): array
    {
        return [
            'user_id' => random_int(1,4),
            'street' => fake()->streetAddress(),
            'number' => random_int(100, 1000),
            'zip' => fake()->postcode(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country()
        ];
    }
}
