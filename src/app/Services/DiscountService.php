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
            throw new HttpException(403, 'Access denied.');
        }

        return $this->discountRepositories->getAllDiscounts();
    }

    public function createDiscount(array $data, $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(403, 'Access denied.');
        }

        /*
            Return a list of all the discounts for a product
        */
        $allDiscountsForAProduct = $this->discountRepositories->getAllDiscountForProduct($data['product_id']);
        
        /*
            I go through all the existing discounts and check if it will go over 60%
        */
        $DiscountLimit = 0;
        
        foreach ($allDiscountsForAProduct as $limit) {
            $DiscountLimit += $limit->discount_percentage;
        }
        if (($DiscountLimit + $data['discount_percentage']) > 60) {
            throw new HttpException(422, 'Discount cannot exceed 60%');
        }

        $discount = $this->discountRepositories->createDiscount($data);

        return $discount;
    }

    public function getDiscount(int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(403, 'Access denied.');
        }

        return $this->discountRepositories->getDiscount($id);
    }

    public function updateDiscount(array $data, int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(403, 'Access denied.');
        }

        return $this->discountRepositories->updateDiscount($data, $id);
    }

    public function deleteDiscount(int $id, int $user_id)
    {
        $this->discountRepositories->getDiscount($id);

        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(403, 'Access denied.');
        }

        return $this->discountRepositories->deleteDiscount($id);
    }
}
