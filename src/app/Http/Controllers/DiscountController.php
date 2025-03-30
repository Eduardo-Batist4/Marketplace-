<?php

namespace App\Http\Controllers;

use App\Services\DiscountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function __construct(protected DiscountService $discountService) {}

    public function index()
    {
        return $this->discountService->getAllDiscounts(Auth::id());
    }

    public function store(Request $request)
    {
        $validateDate = $request->validate([
            'description' => 'required|string|max:255',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'product_id' => 'required|numeric|exists:products,id'
        ]);

        $discount = $this->discountService->createDiscount($validateDate, Auth::id());

        return response()->json([
            'message' => 'Discount successfully created!',
            'discount' => $discount
        ], 201);
    }

    public function show(string $id)
    {
        return $this->discountService->getDiscount($id, Auth::id());
    }

    public function update(Request $request, string $id)
    {
        $validateDate = $request->validate([
            'description' => 'required|string|max:255',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'product_id' => 'required|numeric|exists:products,id'
        ]);

        $discount = $this->discountService->updateDiscount($validateDate, $id, Auth::id());

        return response()->json([
            'message' => 'Discount successfully updated!',
            'discount' => $discount
        ], 200);
    }

    public function destroy(string $id)
    {
        return response($this->discountService->deleteCategory($id, Auth::id()), 204);
    }
}
