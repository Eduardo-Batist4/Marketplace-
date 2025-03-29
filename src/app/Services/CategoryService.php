<?php

namespace App\Services;

use App\Repositories\CategoryRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryService 
{

    public function __construct(protected CategoryRepositories $categoryRepositories, protected UserRepositories $userRepositories)
    {
        
    }

    public function getAllCategories(int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }

        return $this->categoryRepositories->getAllCategory();
    }

    public function createCategory(array $data, $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }

        $category = $this->categoryRepositories->createCategory($data);
        return $category;
    }

    public function getCategory(int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }

        return $this->categoryRepositories->getCategory($id);
    }

    public function updateCategory(array $data, int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }

        return $this->categoryRepositories->updateCategory($data, $id);
    }

    public function deleteCategory(int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.'); 
        }

        return $this->categoryRepositories->deleteCategory($id);
    }
}