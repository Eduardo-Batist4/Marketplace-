<?php

namespace App\Services;

use App\Exceptions\InsufficientQuantityException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartItemService
{
    private function getTotal($items): float
    {
        return $items->cartItems->sum(fn($item) => $item->product->price * $item->quantity);
    }

    public function getAllCartItems(int $userId): array
    {
        $cart = Cart::where('user_id', $userId)
            ->with('cartItems.product')
            ->firstOrFail();
        $totalCart = $this->getTotal($cart);

        return [
            "items" => $cart->cartItems,
            'total' => $totalCart
        ];
    }

    public function createCartItem(array $data, int $userId): CartItem
    {
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        $data['cart_id'] = $cart->id;
        $productPrice = Product::findOrFail($data['product_id']);
        $data['unit_price'] = $productPrice->price;

        if ($data['quantity'] > $productPrice->stock) {
            throw new InsufficientQuantityException($cart->id, $data['quantity'], $productPrice->stock);
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
            throw new InsufficientQuantityException($cart->id, $data['quantity'], $cartItems->product->stock);
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
