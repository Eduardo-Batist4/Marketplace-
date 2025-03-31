<?php

namespace App\Http\Controllers;

use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function __construct(protected CouponService $couponService) {}

    public function index()
    {
        return $this->couponService->getAllCoupon(Auth::id());
    }

    public function store(Request $request)
    {
        $validateDate = $request->validate([
            'code' => 'required|string|min:2|max:5',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $coupon = $this->couponService->createCoupon($validateDate, Auth::id());

        return response()->json([
            'message' => 'Coupon successfully created!',
            'coupon' => $coupon
        ], 201);
    }

    public function show(string $id)
    {
        return $this->couponService->getCoupon($id, Auth::id());
    }

    public function update(Request $request, string $id)
    {
        $validateDate = $request->validate([
            'code' => 'sometimes|string|min:2|max:5',
            'discount_percentage' => 'sometimes|numeric|min:1|max:100',
            'start_date' => 'sometimes|date|after_or_equal:today',
            'end_date' => 'sometimes|date|after:start_date',
        ]);

        $coupon = $this->couponService->updateCoupon($validateDate, $id, Auth::id());

        return response()->json([
            'message' => 'Coupon successfully updated!',
            'coupon' => $coupon
        ], 200);
    }
}
