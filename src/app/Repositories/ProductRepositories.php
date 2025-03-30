<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepositories
{
    public function getAllProducts()
    {
        return Product::all();
    }

    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    public function getProduct(int $id)
    {
        return Product::findOrFail($id);
    }

    public function updateProduct(array $data, int $id)
    {
        $product = $this->getProduct($id);

        $product->update($data);
        return $product;
    }

    public function deleteProduct(int $id)
    {
        return Product::destroy($id);
    }
}
