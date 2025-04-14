<?php

namespace App\Services;

use App\Repositories\CouponRepositories;
use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CouponService
{

    public function __construct(
        protected CouponRepositories $couponRepositories,
        protected UserRepositories $userRepositories
    ) {}

    public function getAllCoupon()
    {
        try {
            return $this->couponRepositories->getAllCoupons();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createCoupon(array $data)
    {
        try {
            $coupon = $this->couponRepositories->createCoupon($data);
            return $coupon;
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getCoupon(int $id)
    {
        try {
            return $this->couponRepositories->getCoupon($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateCoupon(array $data, int $id)
    {
        try {
            $this->couponRepositories->getCoupon($id);
            return $this->couponRepositories->updateCoupon($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteCoupon(int $id)
    {
        try {
            $this->couponRepositories->getCoupon($id);
            return $this->couponRepositories->deleteCoupon($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
