<?php

namespace App\Services;

use App\Repositories\AddressRepositories;
use App\Repositories\OrdersRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderService
{

    public function __construct(
        protected OrdersRepositories $ordersRepositories,
        protected UserRepositories $userRepositories,
        protected AddressRepositories $addressRepositories
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

        DB::beginTransaction();
        try {

        } catch (error) {

        }

        return $user->cart->cartItems;
    }

    // public function getCategory(int $id, int $user_id)
    // {
    //     if (!$this->userRepositories->userIsAdmin($user_id)) {
    //         throw new HttpException(401, 'You do not have authorization.');
    //     }

    //     return $this->categoryRepositories->getCategory($id);
    // }

    // public function updateCategory(array $data, int $id, int $user_id)
    // {
    //     if (!$this->userRepositories->userIsAdmin($user_id)) {
    //         throw new HttpException(401, 'You do not have authorization.');
    //     }

    //     return $this->categoryRepositories->updateCategory($data, $id);
    // }

    // public function deleteCategory(int $id, int $user_id)
    // {
    //     $this->categoryRepositories->findCategory($id);

    //     if (!$this->userRepositories->userIsAdmin($user_id)) {
    //         throw new HttpException(401, 'You do not have authorization.');
    //     }

    //     return $this->categoryRepositories->deleteCategory($id);
    // }
}
