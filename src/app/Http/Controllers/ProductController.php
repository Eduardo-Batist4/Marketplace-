<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{

    public function __construct(protected ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->getAllProducts($request->all());
        return response()->json([ProductResource::collection($products)], 200);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $validateData = $request->validated();
        $validateData['image_path'] = $request->file('image_path');

        $product = $this->productService->createProduct($validateData);

        return response()->json(ProductResource::make($product), 201);
    }

    public function show(string $id): JsonResponse
    {
        $product = $this->productService->getProduct($id);
        return response()->json(ProductResource::make($product), 200);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $validateData = $request->validated();

        $product = $this->productService->updateProduct($validateData, $id);

        return response()->json(ProductResource::make($product), 200);
        }

    public function destroy(int $id): Response
    {
        $this->productService->deleteProduct($id);
        return response()->noContent();
    }
}
