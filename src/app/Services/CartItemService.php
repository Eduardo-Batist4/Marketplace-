<?php

namespace App\Services;

use App\Exceptions\InsufficientQuantityException;
use App\Exceptions\ResourceNotFoundException;
use App\Repositories\CartItemRepositories;
use App\Repositories\CartRepositories;
use App\Repositories\ProductRepositories;

class CartItemService
{
    public function __construct(
        protected CartItemRepositories $cartItemRepositories,
        protected ProductRepositories $productRepositories,
        protected CartRepositories $cartRepositories
    ) {}

    public function getAllCartItems(int $id)
    {
        $cart = $this->cartRepositories->getCartWithUserID($id)->load('cartItems');
        return $cart;
    }

    public function createCartItem(array $data, int $id)
    {
        $cart = $this->cartRepositories->getCartWithUserID($id);
        $data['cart_id'] = $cart->id;

        $productPrice = $this->productRepositories->getProduct($data['product_id']);
        $data['unit_price'] = $productPrice->price;

        if ($data['quantity'] > $productPrice->stock) {
            throw new InsufficientQuantityException();
        }

        return $this->cartItemRepositories->createCartItem($data);
    }

    public function updateCartItem(array $data, string $id)
    {
        $cartItems = $this->cartItemRepositories->getCartItem($id);

        $product = $this->productRepositories->getProduct($cartItems->product_id);

        if ($data['quantity'] > $product->stock) {
            throw new InsufficientQuantityException();
        }

        return $this->cartItemRepositories->updateCartItem($data, $id);
    }

    public function deleteCartItem(string $id)
    {
        $this->cartItemRepositories->getCartItem($id);
        return $this->cartItemRepositories->deleteCartItem($id);
    }
}
