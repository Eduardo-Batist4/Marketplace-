<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Services\DiscountService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class DiscountController extends Controller
{
    public function __construct(protected DiscountService $discountService) {}

    public function index(): AnonymousResourceCollection
    {
        $discounts = $this->discountService->getAllDiscounts();

        return DiscountResource::collection($discounts);
    }

    public function store(StoreDiscountRequest $request): DiscountResource
    {
        $validateDate = $request->validated();

        $discount = $this->discountService->createDiscount($validateDate);

        return DiscountResource::make($discount);
    }

    public function show(int $id): DiscountResource
    {
        $discount = $this->discountService->getDiscount($id);
        return DiscountResource::make($discount);
    }

    public function update(UpdateDiscountRequest $request, string $id): DiscountResource
    {
        $validateDate = $request->validated();

        $discount = $this->discountService->updateDiscount($validateDate, $id);
        return DiscountResource::make($discount);
    }

    public function destroy(int $id): Response
    {
        $this->discountService->deleteDiscount($id);

        return response()->noContent();
    }
}
