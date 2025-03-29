<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepositories
{

    public function createCart(array $data)
    {
        return Cart::create($data);
    }

}
