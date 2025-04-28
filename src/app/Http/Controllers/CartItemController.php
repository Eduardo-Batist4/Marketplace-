<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Services\CartItemService;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartItemController extends Controller
{
    public function __construct(protected CartItemService $cartItemService) {}

    public function index()
    {
        return $this->cartItemService->getAllCartItems(JWTAuth::user()->id);
    }

    public function store(StoreCartItemRequest $request)
    {
        $validateDate = $request->validated();

        $cartItem = $this->cartItemService->createCartItem($validateDate, JWTAuth::user()->id);

        return response()->json([
            'message' => 'Successfully added to cart!',
            'cartItem' => $cartItem
        ], 201);
    }

    public function update(UpdateCartItemRequest $request, int $id)
    {
        $validateDate = $request->validated();

        $category = $this->cartItemService->updateCartItem($validateDate, $id);

        return response()->json($category, 200);
    }

    public function destroy(int $id)
    {
        $this->cartItemService->deleteCartItem($id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);  
    }
}
