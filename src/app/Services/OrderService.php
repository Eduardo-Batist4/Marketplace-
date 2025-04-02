<?php

namespace App\Services;

use App\Repositories\AddressRepositories;
use App\Repositories\OrdersRepositories;
use App\Repositories\ProductRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderService
{

    public function __construct(
        protected OrdersRepositories $ordersRepositories,
        protected UserRepositories $userRepositories,
        protected AddressRepositories $addressRepositories,
        protected ProductRepositories $productRepositories
    ) {}

    public function getAllOrder()
    {
        return $this->ordersRepositories->getAllOrder();
    }

    public function createOrder(array $data, int $user_id)
    {
        $user = $this->userRepositories->getUser($user_id)->load('address');
        $data['user_id'] = $user->id;

        $address = $this->addressRepositories->getAddressWithUser($user->id, $data['address_id']);
        if (!$address) {
            throw new HttpException(400, 'Address not found!');
        }

        $order = $this->ordersRepositories->createOrder($data);



        return $user->cart->cartItems;
    }
}
