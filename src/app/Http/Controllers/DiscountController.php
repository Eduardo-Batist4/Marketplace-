<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Services\DiscountService;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function __construct(protected DiscountService $discountService) {}

    public function index()
    {
        return $this->discountService->getAllDiscounts(Auth::id());
    }

    public function store(StoreDiscountRequest $request)
    {
        $validateDate = $request->validated();

        $discount = $this->discountService->createDiscount($validateDate, Auth::id());

        return response()->json([
            'message' => 'Successfully created!',
            'discount' => $discount
        ], 201);
    }

    public function show(string $id)
    {
        return $this->discountService->getDiscount($id, Auth::id());
    }

    public function update(UpdateDiscountRequest $request, string $id)
    {
        $validateDate = $request->validated();

        $discount = $this->discountService->updateDiscount($validateDate, $id, Auth::id());

        return response()->json([
            'message' => 'Successfully updated!',
            'discount' => $discount
        ], 200);
    }

    public function destroy(string $id)
    {
        $this->discountService->deleteDiscount($id, Auth::id());

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);   
    }
}
