<?php

namespace Database\Seeders;

use App\Models\Discount;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::insert([
                [
                    'description' => 'Desconto para o Campeão Carioca, Brasileiro, Copa do Brasil, Super Copa, Libertadores e Super Mundial',
                    'discount_percentage' => 5,
                    'start_date' => now()->format('Y-m-d'),
                    'end_date' => now()->addDays(30)->format('Y-m-d'),
                    'product_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'description' => 'Desconto para Pro-Player de Counter Strike 2',
                    'discount_percentage' => 15,
                    'start_date' => now()->format('Y-m-d'),
                    'end_date' => now()->addDays(30)->format('Y-m-d'),
                    'product_id' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'description' => 'Desconto para os dias das Mães',
                    'discount_percentage' => 25,
                    'start_date' => now()->format('Y-m-d'),
                    'end_date' => now()->addDays(30)->format('Y-m-d'),
                    'product_id' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
        ]);
    }
}
