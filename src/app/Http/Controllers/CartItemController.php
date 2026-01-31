<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Services\CartItemService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartItemController extends Controller
{
    public function __construct(protected CartItemService $cartItemService) {}

    public function index(): AnonymousResourceCollection
    {
        $cartItems = $this->cartItemService->getAllCartItems(JWTAuth::user()->id);
        return CartItemResource::collection($cartItems);
    }

    public function store(StoreCartItemRequest $request): CartItemResource
    {
        $validateDate = $request->validated();

        $cartItem = $this->cartItemService->createCartItem($validateDate, JWTAuth::user()->id);

        return CartItemResource::make($cartItem);
    }

    public function update(UpdateCartItemRequest $request, int $cartItemId): CartItemResource
    {
        $validateDate = $request->validated();

        $category = $this->cartItemService->updateCartItem($validateDate, JWTAuth::user()->id, $cartItemId);

        return CartItemResource::make($category);
    }

    public function destroy(int $id): Response
    {
        $this->cartItemService->deleteCartItem($id);

        return response()->noContent();
    }
}
