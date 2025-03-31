<?php

namespace App\Http\Controllers;

use App\Services\CartItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function __construct(protected CartItemService $cartItemService) {}

    public function index()
    {
        return $this->cartItemService->getAllCartItems(Auth::id());
    }

    public function store(Request $request)
    {
        $validateDate = $request->validate([
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'required|numeric',
        ]);

        $cartItem = $this->cartItemService->createCartItem($validateDate, Auth::id());

        return response()->json([
            'message' => 'items successfully added to cart!',
            'cartItem' => $cartItem
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validateDate = $request->validate([
            'quantity' => 'required|numeric',
        ]);

        $category = $this->cartItemService->updateCartItem($validateDate, $id);

        return response()->json($category, 200);
    }

    public function destroy(string $id)
    {
        return response($this->cartItemService->deleteCartItem($id), 204);
    }
}
