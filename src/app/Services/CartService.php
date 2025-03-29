<?php

namespace App\Services;

use App\Repositories\CartRepositories;

class CartService
{

    public function __construct(protected CartRepositories $cartRepositories) {}

    public function createCart(array $data)
    {
        return $this->cartRepositories->createCart($data);
    }
}
