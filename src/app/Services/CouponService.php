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

    public function getAllCoupon(int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->couponRepositories->getAllCoupons();
    }

    public function createCoupon(array $data, $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        $coupon = $this->couponRepositories->createCoupon($data);
        return $coupon;
    }

    public function getCoupon(int $id, int $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->couponRepositories->getCoupon($id);
    }

    public function updateCoupon(array $data, int $id, int $user_id)
    {
        $this->couponRepositories->getCoupon($id);

        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->couponRepositories->updateCoupon($data, $id);
    }

    public function deleteCoupon(int $id, int $user_id)
    {
        $this->couponRepositories->getCoupon($id);

        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(401, 'You do not have authorization.');
        }

        return $this->couponRepositories->deleteCoupon($id);
    }
}
