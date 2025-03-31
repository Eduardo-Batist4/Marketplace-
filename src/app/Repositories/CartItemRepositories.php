<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartItemRepositories
{
    
    public function getAllCartItems(int $cart_id) 
    {
        return CartItem::where('cart_id', $cart_id)->get();
    }

    public function createCartItem(array $data)
    {
        return CartItem::create($data);
    }

    public function getCartItem(int $id)
    {
        return CartItem::findOrFail($id);        
    }

    public function updateCartItem(array $data, int $id) 
    {
        $cartItem = $this->getCartItem($id);

        $cartItem->update($data);
        return $cartItem;
    }

    public function deleteCartItem(int $id) 
    {
        return CartItem::destroy($id);
    }
}
