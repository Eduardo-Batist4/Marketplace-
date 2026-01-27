<?php

namespace App\Services;

use App\Exceptions\MaxDiscountException;
use App\Models\Discount;
use Illuminate\Pagination\LengthAwarePaginator;

class DiscountService
{
    public function getAllDiscounts(): LengthAwarePaginator
    {
        return Discount::paginate(2);
    }

    public function createDiscount(array $data): Discount
    {
        $allDiscountsForAProduct = Discount::where('product_id', $data['product_id'])->get();
        $DiscountLimit = 0;

        foreach ($allDiscountsForAProduct as $limit) {
            $DiscountLimit += $limit->discount_percentage;
        }
        if (($DiscountLimit + $data['discount_percentage']) > 60) {
            throw new MaxDiscountException();
        }

        $discount = Discount::create($data);

        return $discount;
    }

    public function getDiscount(int $id): Discount
    {
        return Discount::findOrFail($id);
    }

    public function updateDiscount(array $data, int $id): Discount
    {
        $allDiscountsForAProduct = Discount::where('product_id', $data['product_id'])->get();

        $DiscountLimit = 0;

        foreach ($allDiscountsForAProduct as $limit) {
            $DiscountLimit += $limit->discount_percentage;
        }
        if (($DiscountLimit + $data['discount_percentage']) > 60) {
            throw new MaxDiscountException();
        }

        $discount = Discount::findOrFail($id);
        $discount->update($data);

        return $discount->fresh();
    }

    public function deleteDiscount(int $id): bool
    {
        $discount = Discount::findOrFail($id);
        return $discount->delete();
    }
}
