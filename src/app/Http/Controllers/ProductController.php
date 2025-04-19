<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct(protected ProductService $productService) {}

    public function index(Request $request)
    {
        return $this->productService->getAllProducts($request->all());
    }

    public function store(StoreProductRequest $request)
    {
        $request->validated();

        $product = $this->productService->createProduct($request);

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

        $product = $this->productService->updateProduct($validateData, $id);

        return response()->json([
            'message' => 'Successfully updated!',
            'product' => $product
        ], 200);
    }

    public function destroy(string $id)
    {
        $this->productService->deleteProduct($id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);
    }
}
