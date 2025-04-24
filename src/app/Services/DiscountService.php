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

    public function getAllDiscounts()
    {
        try {
            return $this->discountRepositories->getAllDiscounts();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createDiscount(array $data)
    {
        try {
            $allDiscountsForAProduct = $this->discountRepositories->getAllDiscountForProduct($data['product_id']);

            $DiscountLimit = 0;

            foreach ($allDiscountsForAProduct as $limit) {
                $DiscountLimit += $limit->discount_percentage;
            }
            if (($DiscountLimit + $data['discount_percentage']) > 60) {
                throw new HttpException(422, 'Discount cannot exceed 60%');
            }

            $discount = $this->discountRepositories->createDiscount($data);

            return $discount;
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getDiscount(int $id)
    {
        try {
            return $this->discountRepositories->getDiscount($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateDiscount(array $data, int $id)
    {
        try {
            return $this->discountRepositories->updateDiscount($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteDiscount(int $id)
    {
        try {
            $this->discountRepositories->getDiscount($id);
            return $this->discountRepositories->deleteDiscount($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
