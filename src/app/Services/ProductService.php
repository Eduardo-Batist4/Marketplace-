<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService
{
    public function getAllProducts(array $filter): LengthAwarePaginator
    {
        return Product::applyFilters($filter)->with('discounts')->paginate(15);
    }

    public function createProduct(array $data): Product
    {
        $image = $data['image_path'];
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

        $path = Storage::putFileAs('public/products', $image, $imageName);

        $data['image_path'] = $path;

        $product = Product::create($data);
        return $product;
    }

    public function getProduct(int $id): Product
    {
        /*
            If there are many feedbacks, a new route must be created to load the feedbacks with pagination
        */
        return Product::where('id', $id)->with(['discounts', 'feedbacks'])->first();
    }

    public function updateProduct(array $data, int $id): Product
    {
        $product = Product::findOrFail($id);

        $product->update($data);

        return $product->fresh();
    }

    public function deleteProduct(int $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}
