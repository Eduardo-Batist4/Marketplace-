<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepositories
{

    public function findCategory(int $id)
    {
        return Category::findOrFail($id);
    }

    public function getAllCategory()
    {
        return Category::all();
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function getCategory(int $id)
    {
        $category = $this->findCategory($id)->load('products');

        return $category;
    }

    public function updateCategory(array $data, int $id)
    {
        $category = $this->findCategory($id);

        $category->update($data);
        return $category;
    }

    public function deleteCategory(int $id)
    {
        return Category::destroy($id);
    }
}
