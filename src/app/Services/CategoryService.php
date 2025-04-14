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
        try {
            return $this->categoryRepositories->getAllCategory();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createCategory(array $data)
    {
        try {
            $category = $this->categoryRepositories->createCategory($data);
            return $category;
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getCategory(int $id)
    {
        try {
            return $this->categoryRepositories->getCategory($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateCategory(array $data, int $id)
    {
        try {
            return $this->categoryRepositories->updateCategory($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteCategory(int $id)
    {
        try {
            $this->categoryRepositories->findCategory($id);
            return $this->categoryRepositories->deleteCategory($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
