<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Services\DiscountService;

class DiscountController extends Controller
{
    public function __construct(protected DiscountService $discountService) {}

    public function index()
    {
        return $this->discountService->getAllDiscounts();
    }

    public function store(StoreDiscountRequest $request)
    {
        $validateDate = $request->validated();

        $discount = $this->discountService->createDiscount($validateDate);

        return response()->json([
            'message' => 'Successfully created!',
            'discount' => $discount
        ], 201);
    }

    public function show(string $id)
    {
        return $this->discountService->getDiscount($id);
    }

    public function update(UpdateDiscountRequest $request, string $id)
    {
        $validateDate = $request->validated();

        $discount = $this->discountService->updateDiscount($validateDate, $id);

        return response()->json([
            'message' => 'Successfully updated!',
            'discount' => $discount
        ], 200);
    }

    public function destroy(string $id)
    {
        $this->discountService->deleteDiscount($id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);
    }
}
