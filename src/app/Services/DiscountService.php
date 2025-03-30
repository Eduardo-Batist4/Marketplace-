<?php

namespace App\Services;

use App\Repositories\DiscountRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DiscountService
{

    public function __construct(
        protected DiscountRepositories $discountRepositories,
        protected UserRepositories $userRepositories
    ) {}

    public function getAllDiscounts(int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->discountRepositories->getAllDiscounts();
    }

    public function createDiscount(array $data, $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        $discount = $this->discountRepositories->createDiscount($data);
        return $discount;
    }

    public function getDiscount(int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->discountRepositories->getDiscount($id);
    }

    public function updateDiscount(array $data, int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->discountRepositories->updateDiscount($data, $id);
    }

    public function deleteCategory(int $id, int $user_id)
    {
        $this->discountRepositories->getDiscount($id);

        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->discountRepositories->deleteDiscount($id);
    }
}
