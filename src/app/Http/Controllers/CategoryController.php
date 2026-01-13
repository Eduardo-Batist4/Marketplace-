<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    public function __construct(protected CategoryService $categoryService) {}

    public function index(): AnonymousResourceCollection
    {
        $categories = $this->categoryService->getAllCategories();
        return  CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $category = $this->categoryService->createCategory($validatedData);

        return CategoryResource::make($category)->response()->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryService->getCategory($id);
        return CategoryResource::make($category)->response();
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $validatedData = $request->validated();

        $category = $this->categoryService->updateCategory($validatedData, $id);

        return CategoryResource::make($category)->response()->setStatusCode(200);
    }

    public function destroy(int $id): Response
    {
        $this->categoryService->deleteCategory($id);
        return response()->noContent();
    }
}
