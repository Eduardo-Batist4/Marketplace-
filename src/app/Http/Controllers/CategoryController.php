<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService) {}

    public function index()
    {
        return $this->categoryService->getAllCategories(Auth::id());
    }

    public function store(StoreCategoryRequest $request)
    {
        $validateDate = $request->validated();

        $category = $this->categoryService->createCategory($validateDate, Auth::id());

        return response()->json([
            'message' => 'Successfully created!',
            'category' => $category
        ], 201);
    }

    public function show(string $id)
    {
        return $this->categoryService->getCategory($id, Auth::id());
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $validateDate = $request->validated();

        $category = $this->categoryService->updateCategory($validateDate, $id, Auth::id());

        return response()->json([
            'message' => 'Successfully updated!',
            'category' => $category
        ], 200);
    }

    public function destroy(string $id)
    {
        response($this->categoryService->deleteCategory($id, Auth::id()), 204);
        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);  
    }
}
