<?php

namespace App\Services;

use App\Repositories\CartItemRepositories;
use App\Repositories\CartRepositories;
use App\Repositories\ProductRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        /*
            takes the cart of the logged-in user.
            There's no way the user doesn't have a cart!
        */
        $cart = $this->cartRepositories->getCartWithUserID($id);
        $data['cart_id'] = $cart->id;

        
        $productPrice = $this->productRepositories->getProduct($data['product_id']);
        $data['unit_price'] = $productPrice->price;

        /*
           Checking stock
        */
        if ($data['quantity'] > $productPrice->stock) {
            throw new HttpException(422, 'Quantity exceeds available stock.');
        }


        return $this->cartItemRepositories->createCartItem($data);
    }

    public function updateCartItem(array $data, string $id)
    {
        $cartItems = $this->cartItemRepositories->getCartItem($id);

        $product = $this->productRepositories->getProduct($cartItems->product_id);

        /*
           Checking stock
        */
        if ($data['quantity'] > $product->stock) {
            throw new HttpException(422, 'Quantity exceeds available stock.');
        }

        return $this->cartItemRepositories->updateCartItem($data, $id);
    }

    public function deleteCartItem(string $id)
    {
        $cartItems = $this->cartItemRepositories->getCartItem($id);

        return $this->cartItemRepositories->deleteCartItem($id);
    }
}
