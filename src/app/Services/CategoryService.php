<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function getAllCategories(): LengthAwarePaginator
    {
        return Category::paginate(15);
    }

    public function createCategory(array $data): Category
    {
        $category = Category::create($data);
        return $category;
    }

    public function getCategory(int $id): Category
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    public function updateCategory(array $data, int $id): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category->fresh();
    }

    public function deleteCategory(int $id): bool
    {
        $category = Category::findOrFail($id);
        return $category->delete();
    }
}
