<?php

namespace App\Repositories;

use App\Models\Discount;

class DiscountRepositories
{
    public function getAllDiscounts()
    {
        return Discount::all();
    }

    public function createDiscount(array $data)
    {
        return Discount::create($data);
    }

    public function getDiscount(int $id)
    {
        return Discount::findOrFail($id);
    }

    public function updateDiscount(array $data, int $id)
    {
        $discount = $this->getDiscount($id);

        $discount->update($data);
        return $discount;
    }

    public function deleteDiscount(int $id)
    {
        return Discount::destroy($id);
    }
}
