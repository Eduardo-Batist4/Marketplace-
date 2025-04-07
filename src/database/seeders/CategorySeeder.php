<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    use WithoutModelEvents;

    public function run(): void
    {
        Category::create([
            'name' => 'Roupas',
            'description' => 'Coleções com peças casuais, sociais e esportivas.'
        ]);

        Category::create([
            'name' => 'Eletronicos',
            'description' => 'Explore nossa seleção de eletrônicos de última geração.'
        ]);

        Category::create([
            'name' => 'Casa',
            'description' => 'Transforme seu lar com nossa linha de produtos para casa.'
        ]);
    }
}
