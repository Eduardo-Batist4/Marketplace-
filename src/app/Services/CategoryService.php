<?php

namespace App\Services;

use App\Repositories\CategoryRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryService
{

    public function __construct(protected CategoryRepositories $categoryRepositories, protected UserRepositories $userRepositories) {}

    public function getAllCategories()
    {
        return $this->categoryRepositories->getAllCategory();
    }

    public function createCategory(array $data)
    {
        $category = $this->categoryRepositories->createCategory($data);
        return $category;
    }

    public function getCategory(int $id)
    {
        return $this->categoryRepositories->getCategory($id);
    }

    public function updateCategory(array $data, int $id)
    {
        return $this->categoryRepositories->updateCategory($data, $id);
    }

    public function deleteCategory(int $id)
    {
        $this->categoryRepositories->findCategory($id);

        return $this->categoryRepositories->deleteCategory($id);
    }
}
