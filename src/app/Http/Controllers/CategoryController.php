<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService) {}

    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();
        return response()->json(
            CategoryResource::collection($categories),
            201
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $category = $this->categoryService->createCategory($validatedData);

        return response()->json(
            CategoryResource::make($category),
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategory($id);
        return response()->json(
            CategoryResource::make($category),
            200
        );
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $validatedData = $request->validated();

        $category = $this->categoryService->updateCategory($validatedData, $id);

        return response()->json(
            CategoryResource::make($category),
            200
        );
    }

    public function destroy(int $id): Response
    {
        $this->categoryService->deleteCategory($id);
        return response()->noContent();
    }
}
