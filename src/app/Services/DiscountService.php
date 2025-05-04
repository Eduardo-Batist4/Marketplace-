<?php

namespace App\Services;

use App\Exceptions\MaxDiscountException;
use App\Repositories\DiscountRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DiscountService
{

    public function __construct(
        protected DiscountRepositories $discountRepositories,
        protected UserRepositories $userRepositories
    ) {}

    public function getAllDiscounts()
    {
        return $this->discountRepositories->getAllDiscounts();
    }

    public function createDiscount(array $data)
    {
        $allDiscountsForAProduct = $this->discountRepositories->getAllDiscountForProduct($data['product_id']);

        $DiscountLimit = 0;

        foreach ($allDiscountsForAProduct as $limit) {
            $DiscountLimit += $limit->discount_percentage;
        }
        if (($DiscountLimit + $data['discount_percentage']) > 60) {
            throw new MaxDiscountException();
        }

        $discount = $this->discountRepositories->createDiscount($data);

        return $discount;
    }

    public function getDiscount(int $id)
    {
        return $this->discountRepositories->getDiscount($id);
    }

    public function updateDiscount(array $data, int $id)
    {
        $allDiscountsForAProduct = $this->discountRepositories->getAllDiscountForProduct($data['product_id']);

        $DiscountLimit = 0;

        foreach ($allDiscountsForAProduct as $limit) {
            $DiscountLimit += $limit->discount_percentage;
        }
        if (($DiscountLimit + $data['discount_percentage']) > 60) {
            throw new MaxDiscountException();
        }

        return $this->discountRepositories->updateDiscount($data, $id);
    }

    public function deleteDiscount(int $id)
    {
        $this->discountRepositories->getDiscount($id);
        return $this->discountRepositories->deleteDiscount($id);
    }
}
