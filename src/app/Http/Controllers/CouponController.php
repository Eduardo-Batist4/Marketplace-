<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Services\CouponService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CouponController extends Controller
{
    public function __construct(protected CouponService $couponService) {}

    public function index(): AnonymousResourceCollection
    {
        $coupons = $this->couponService->getAllCoupon();
        return CouponResource::collection($coupons);
    }

    public function store(StoreCouponRequest $request): CouponResource
    {
        $validateDate = $request->validated();

        $coupon = $this->couponService->createCoupon($validateDate);

        return CouponResource::make($coupon);
    }

    public function show(int $id): CouponResource
    {
        $coupon = $this->couponService->getCoupon($id);
        return CouponResource::make($coupon);
    }

    public function update(UpdateCouponRequest $request, int $id): CouponResource
    {
        $validateDate = $request->validated();

        $coupon = $this->couponService->updateCoupon($validateDate, $id);

        return CouponResource::make($coupon);
    }

    public function destroy(string $id): Response
    {
        $this->couponService->deleteCoupon($id);
        return response()->noContent();
    }
}
