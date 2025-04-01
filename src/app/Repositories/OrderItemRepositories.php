<?php

namespace App\Repositories;

use App\Models\OrderItems;

class OrderItemRepositories
{

    public function getAllOrderItems()
    {
        return OrderItems::all();
    }

    public function createOrderItem(array $data)
    {
        return OrderItems::create($data);
    }

    public function getOrderItem(int $id)
    {
        $orderItem = OrderItems::findOrFail($id);

        return $orderItem;
    }

    public function updateOrderItem(array $data, int $id)
    {
        $orderItem = OrderItems::findOrFail($id);

        $orderItem->update($data);
        return $orderItem;
    }

    public function deleteOrderItem(int $id)
    {
        return OrderItems::destroy($id);
    }
}
