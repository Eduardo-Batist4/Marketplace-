<?php

namespace App\Services;

use App\Repositories\CartRepositories;

class CartService
{

    public function __construct(protected CartRepositories $cartRepositories) {}

    public function createCart(array $data)
    {
        try {
            return $this->cartRepositories->createCart($data);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
