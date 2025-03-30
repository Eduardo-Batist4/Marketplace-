<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct(protected ProductService $productService) {}

    public function index()
    {
        return $this->productService->getAllProducts();
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|min:4|max:200',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required|numeric|exists:categories,id',
            'image_path' => 'sometimes|string',
            'description' => 'sometimes|string|max:255' 
        ]);

        $product = $this->productService->createProduct($validateData, Auth::id());

        return response()->json([
            'message' => 'Product successfully created!',
            'product' => $product
        ], 201);
    }

    public function show(string $id)
    {
        return $this->productService->getProduct($id);
    }

    public function update(Request $request, string $id) 
    {
        $validateData = $request->validate([
            'name' => 'sometimes|string|min:4|max:200',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|numeric',
            'category_id' => 'sometimes|numeric|exists:categories,id',
            'image_path' => 'sometimes|string',
            'description' => 'sometimes|string|max:255' 
        ]);

        $product = $this->productService->updateProduct($validateData, $id, Auth::id());

        return response()->json([
            'message' => 'Product successfully updated!',
            'product' => $product
        ], 200);
    }

    public function destroy(string $id)
    {
        return response($this->productService->deleteProduct($id, Auth::id()), 204);
    }
}
