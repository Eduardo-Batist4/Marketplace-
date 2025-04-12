<?php

namespace App\Services;

use App\Repositories\ProductRepositories;
use App\Repositories\UserRepositories;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;

class ProductService
{

    public function __construct(
        protected ProductRepositories $productRepositories,
        protected UserRepositories $userRepositories
    ) {}

    public function getAllProducts()
    {
        $product = $this->productRepositories->getAllProducts();

        if (!$product) {
            return response()->json('No registered product!');
        }

        return $product;
    }

    public function createProduct($request)
    {
        $imageName = Str::uuid() . '.' . $request->file('image_path')->getClientOriginalExtension();

        $path = Storage::putFileAs('public/profiles', $request->file('image_path'), $imageName);

        $data = $request->all();

        $data['image_path'] = $path;

        return $this->productRepositories->createProduct($data);
    }

    public function getProduct(int $id)
    {
        return $this->productRepositories->getProduct($id);
    }

    public function updateProduct(array $data, string $id)
    {
        return $this->productRepositories->updateProduct($data, $id);
    }

    public function deleteProduct(string $id)
    {
        $this->productRepositories->getProduct($id);
        return $this->productRepositories->deleteProduct($id);
    }
}
