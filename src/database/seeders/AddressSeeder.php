<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    use WithoutModelEvents;

    public function run(): void
    {

        Address::create([
            'user_id' => 1,
            'street' => 'Rua do Admin',
            'number' => 215,
            'zip' => '87224-4435',
            'city' => 'Detroit',
            'state' => 'Michigan',
            'country' => 'EUA'
        ]);

        Address::factory(5)->create();
    }
}
