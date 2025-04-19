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

    public function getAllProducts(array $filter)
    {
        try {
            $product = $this->productRepositories->getAllProducts($filter);

            if (!$product) {
                return response()->json('No registered product!');
            }

            return $product;
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createProduct($request)
    {
        try {
            $imageName = Str::uuid() . '.' . $request->file('image_path')->getClientOriginalExtension();

            $path = Storage::putFileAs('public/profiles', $request->file('image_path'), $imageName);

            $data = $request->all();

            $data['image_path'] = $path;

            return $this->productRepositories->createProduct($data);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getProduct(int $id)
    {
        try {
            return $this->productRepositories->getProduct($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateProduct(array $data, string $id)
    {
        try {
            return $this->productRepositories->updateProduct($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteProduct(string $id)
    {
        try {
            $this->productRepositories->getProduct($id);
            return $this->productRepositories->deleteProduct($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
