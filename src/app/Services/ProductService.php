<?php

namespace App\Services;

use App\Exceptions\NoRegisteredProductException;
use App\Repositories\ProductRepositories;
use App\Repositories\UserRepositories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function PHPUnit\Framework\isEmpty;

class ProductService
{

    public function __construct(
        protected ProductRepositories $productRepositories,
        protected UserRepositories $userRepositories
    ) {}

    public function getAllProducts(array $filter)
    {
        $product = $this->productRepositories->getAllProducts($filter);

        return $product;
    }

    public function createProduct(array $data)
    {
        $image = $data['image_path'];
        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

        $path = Storage::putFileAs('public/products', $image, $imageName);

        $data['image_path'] = $path;

        return $this->productRepositories->createProduct($data);
    }

    public function getProduct(int $id)
    {
        return $this->productRepositories->getProduct($id);
    }

    public function updateProduct(array $data, int $id)
    {
        return $this->productRepositories->updateProduct($data, $id);
    }

    public function deleteProduct(int $id)
    {
        $this->productRepositories->getProduct($id);
        return $this->productRepositories->deleteProduct($id);
    }
}
