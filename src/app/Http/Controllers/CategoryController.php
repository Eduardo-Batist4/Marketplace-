<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService) {}

    public function index()
    {
        return $this->categoryService->getAllCategories(Auth::id());
    }

    public function store(Request $request)
    {
        $validateDate = $request->validate([
            'name' => 'required|string|min:4|max:100',
            'description' => 'sometimes|string|max:255'
        ]);

        $category = $this->categoryService->createCategory($validateDate, Auth::id());

        return response()->json([
            'message' => 'Category successfully created!',
            'category' => $category
        ], 201);
    }

    public function show(string $id)
    {
        return $this->categoryService->getCategory($id, Auth::id());
    }

    public function update(Request $request, string $id)
    {
        $validateDate = $request->validate([
            'name' => 'sometimes|string|min:4|max:100',
            'description' => 'sometimes|string|max:255'
        ]);

        $category = $this->categoryService->updateCategory($validateDate, $id, Auth::id());

        return response()->json([
            'message' => 'Category successfully updated!',
            'category' => $category
        ], 200);
    }

    public function destroy(string $id)
    {
        return response($this->categoryService->deleteCategory($id, Auth::id()), 204);
    }
}
