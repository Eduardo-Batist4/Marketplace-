<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
    */

    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Eduardo',
            'email' => 'admin@teste.com',
            'password' => 'Senha123',
            'image_path' => null,
            'role' => 'admin'
        ]);

        User::factory(3)->create();
    }
}
