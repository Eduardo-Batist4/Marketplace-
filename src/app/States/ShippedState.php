<?php

namespace App\States;

use App\Jobs\SendOrderStatusEmail;
use App\Models\Order;

class ShippedState extends AbstractOrderState
{
    protected array $allowedTransitions = ['completed', 'canceled'];

    protected function afterTransition(Order $order, string $newStatus): void
    {
        SendOrderStatusEmail::dispatch($order->user->email, $order);
    }
}
