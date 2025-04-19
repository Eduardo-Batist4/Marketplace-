<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepositories
{
    public function getAllProducts(array $filter)
    {
        $query = Product::query();

        if (!empty($filter['name'])) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if (!empty($filter['category_id'])) {
            $query->where('category_id', 'like', '%' . $filter['category_id'] . '%');
        }

        if (!empty($filter['min_price'])) {
            $query->where('price' , '>=', $filter['min_price']);
        }

        if (!empty($filter['max_price'])) {
            $query->where('price' , '<=', $filter['max_price']);
        }

        return $query->with('discounts')->get();
    }

    public function createProduct($data)
    {
        return Product::create($data);
    }

    public function getProduct(int $id)
    {
        return Product::findOrFail($id)->load('discounts');
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
