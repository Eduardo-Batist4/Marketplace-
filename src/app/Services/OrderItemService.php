<?php

namespace App\Services;

use App\Repositories\OrderItemRepositories;

class OrderItemService
{

    public function __construct(
        protected OrderItemRepositories $orderItemRepositories,
    ) {}

    public function createOrderItem(array $data)
    {
        try {
            return $this->orderItemRepositories->createOrderItem($data);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }


}