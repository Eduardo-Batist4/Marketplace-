<?php

namespace App\Services;

use App\Repositories\CouponRepositories;

class CouponService
{

    public function __construct(protected CouponRepositories $couponRepositories) {}

    public function getAllCoupon()
    {
        return $this->couponRepositories->getAllCoupons();
    }

    public function createCoupon(array $data)
    {
        $coupon = $this->couponRepositories->createCoupon($data);
        return $coupon;
    }

    public function getCoupon(int $id)
    {
        return $this->couponRepositories->getCoupon($id);
    }

    public function updateCoupon(array $data, int $id)
    {
        $this->couponRepositories->getCoupon($id);
        return $this->couponRepositories->updateCoupon($data, $id);
    }

    public function deleteCoupon(int $id)
    {
        $this->couponRepositories->getCoupon($id);
        return $this->couponRepositories->deleteCoupon($id);
    }
}
