<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Services\CouponService;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function __construct(protected CouponService $couponService) {}

    public function index()
    {
        return $this->couponService->getAllCoupon();
    }

    public function store(StoreCouponRequest $request)
    {
        $validateDate = $request->validated();

        $coupon = $this->couponService->createCoupon($validateDate);

        return response()->json([
            'message' => 'Successfully created!',
            'coupon' => $coupon
        ], 201);
    }

    public function show(string $id)
    {
        return $this->couponService->getCoupon($id);
    }

    public function update(UpdateCouponRequest $request, string $id)
    {
        $validateDate = $request->validated();

        $coupon = $this->couponService->updateCoupon($validateDate, $id);

        return response()->json([
            'message' => 'Successfully updated!',
            'coupon' => $coupon
        ], 200);
    }

    public function destroy(string $id)
    {
        $this->couponService->deleteCoupon($id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);     
    }
}
