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
        return $this->orderItemRepositories->createOrderItem($data);
    }
}
