<?php

namespace App\Repositories;

use App\Models\OrdersItem;

class OrdersRepositories
{

    public function getAllOrderItems()
    {
        return OrdersItem::all();
    }

    public function createOrderItem(array $data)
    {
        return OrdersItem::create($data);
    }

    public function getOrderItem(int $id)
    {
        $orderItem = OrdersItem::findOrFail($id);

        return $orderItem;
    }

    public function updateOrderItem(array $data, int $id)
    {
        $orderItem = OrdersItem::findOrFail($id);

        $orderItem->update($data);
        return $orderItem;
    }

    public function deleteOrderItem(int $id)
    {
        return OrdersItem::destroy($id);
    }
}
