<?php

namespace App\Services;

use App\Repositories\ProductRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function createProduct(array $data, $user_id)
    {
        if (!$this->userRepositories->userIsAdminOrModerator($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }

        return $this->productRepositories->createProduct($data);
    }

    public function getProduct(int $id)
    {
        return $this->productRepositories->getProduct($id);
    }

    public function updateProduct(array $data, string $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdminOrModerator($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }

        return $this->productRepositories->updateProduct($data, $id);
    }

    public function deleteProduct(string $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdminOrModerator($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }
        
        $this->productRepositories->deleteProduct($id);
        
        return response(204);
    }
}
