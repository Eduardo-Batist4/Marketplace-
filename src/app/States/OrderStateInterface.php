<?php

namespace App\States;

use App\Models\Order;

interface OrderStateInterface
{
    public function transitionTo(Order $order, string $newStatus): void;
};
