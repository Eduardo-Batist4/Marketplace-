<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Pagination\LengthAwarePaginator;

class CouponService
{
    public function getAllCoupon(): LengthAwarePaginator
    {
        return Coupon::paginate(15);
    }

    public function createCoupon(array $data): Coupon
    {
        $coupon = Coupon::create($data);
        return $coupon;
    }

    public function getCoupon(int $id): Coupon
    {
        return Coupon::findOrFail($id);
    }

    public function updateCoupon(array $data, int $id): Coupon
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update($data);
        return $coupon->fresh();
    }

    public function deleteCoupon(int $id): bool
    {
        $coupon = Coupon::findOrFail($id);
        return $coupon->delete();
    }
}
