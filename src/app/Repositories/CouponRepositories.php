<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepositories
{
    public function getAllCoupons()
    {
        return Coupon::all();
    }

    public function createCoupon(array $data)
    {
        return Coupon::create($data);
    }

    public function getCoupon(int $id)
    {
        return Coupon::findOrFail($id);
    }

    public function updateCoupon(array $data, int $id)
    {
        $coupon = $this->getCoupon($id);

        $coupon->update($data);
        return $coupon;
    }

    public function deleteCoupon(int $id)
    {
        return Coupon::destroy($id);
    }
}
