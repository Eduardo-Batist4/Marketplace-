<?php

namespace App\Repositories;

use App\Models\Order;

class OrdersRepositories
{

    public function getAllOrder()
    {
        return Order::all();
    }

    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    public function getOrder(int $id)
    {
        $order = Order::findOrFail($id);

        return $order;
    }

    public function updateOrder(array $data, int $id)
    {
        $order = Order::findOrFail($id);

        $order->update($data);
        return $order;
    }

    public function deleteOrder(int $id)
    {
        return Order::destroy($id);
    }
}
