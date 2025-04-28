<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService) {}

    public function index()
    {
        return $this->categoryService->getAllCategories();
    }

    public function store(StoreCategoryRequest $request)
    {
        $validateDate = $request->validated();

        $category = $this->categoryService->createCategory($validateDate);

        return response()->json([
            'message' => 'Successfully created!',
            'category' => $category
        ], 201);
    }

    public function show(int $id)
    {
        return $this->categoryService->getCategory($id);
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        $validateDate = $request->validated();

        $category = $this->categoryService->updateCategory($validateDate, $id);

        return response()->json([
            'message' => 'Successfully updated!',
            'category' => $category
        ], 200);
    }

    public function destroy(int $id)
    {
        response($this->categoryService->deleteCategory($id), 204);
        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);  
    }
}
