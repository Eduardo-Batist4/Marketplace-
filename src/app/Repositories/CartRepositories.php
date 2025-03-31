<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepositories
{

    public function createCart(array $data)
    {
        return Cart::create($data);
    }

    public function getCartWithUserID(int $id)
    {
        return Cart::where('user_id', $id)->first();
    }
}
