<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct(protected ProductService $productService) {}

    public function index()
    {
        return $this->productService->getAllProducts();
    }

    public function store(StoreProductRequest $request)
    {
        $validateData = $request->validated();

        $product = $this->productService->createProduct($validateData, Auth::id());

        return response()->json([
            'message' => 'Successfully created!',
            'product' => $product
        ], 201);
    }

    public function show(string $id)
    {
        return $this->productService->getProduct($id);
    }

    public function update(UpdateProductRequest $request, string $id) 
    {
        $validateData = $request->validated();

        $product = $this->productService->updateProduct($validateData, $id, Auth::id());

        return response()->json([
            'message' => 'Successfully updated!',
            'product' => $product
        ], 200);
    }

    public function destroy(string $id)
    {
        return response($this->productService->deleteProduct($id, Auth::id()), 204);
    }
}
