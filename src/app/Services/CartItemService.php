<?php

namespace App\Services;

use App\Exceptions\InsufficientQuantityException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class CartItemService
{
    public function getAllCartItems(int $userId): Collection
    {
        $cart = Cart::where('user_id', $userId)
            ->with('cartItems.product')
            ->firstOrFail();
        return $cart->cartItems;
    }

    public function createCartItem(array $data, int $userId): CartItem
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        $data['cart_id'] = $cart->id;

        $productPrice = Product::findOrFail($data['product_id']);
        $data['unit_price'] = $productPrice->price;

        if ($data['quantity'] > $productPrice->stock) {
            throw new InsufficientQuantityException();
        }

        return CartItem::create($data);
    }

    public function updateCartItem(array $data, int $userId, int $cartItemId): CartItem
    {
        $cart = Cart::where('user_id', $userId)
            ->firstOrFail();
        $cartItems = CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->with('product')
            ->firstOrFail();

        if ($data['quantity'] > $cartItems->product->stock) {
            throw new InsufficientQuantityException();
        }

        $cartItems->update([
            'quantity' => $data['quantity'],
        ]);
        return $cartItems->fresh();
    }

    public function deleteCartItem(string $id): bool
    {
        $cartItems = CartItem::findOrFail($id);
        return $cartItems->delete();
    }
}
