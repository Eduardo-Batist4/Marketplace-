<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Camiseta Flamengo',
                'price' => 359.99,
                'stock' => 15,
                'category_id' => 1,
                'image_path' => null,
                'description' => 'Camiseta do Flamengo, vermelha e preta, com escudo bordado e design esportivo.',
            ],
            [
                'name' => 'Monitor BenQ ZOWIE 400Hz',
                'price' => 6310.44,
                'stock' => 20,
                'category_id' => 2,
                'image_path' => null,
                'description' => 'Monitor BenQ 400Hz, ultrarrápido, imagem fluida, ideal para jogos competitivos.',
            ],
            [
                'name' => 'Geladeira Eletrolux',
                'price' => 7284.99,
                'stock' => 18,
                'category_id' => 3,
                'image_path' => null,
                'description' => 'Geladeira Electrolux Frost Free Inverter 590L AutoSense 3 Portas Cor Inox Look (IM8S) (220 Volts).',
            ]
        ]);
    }
}
