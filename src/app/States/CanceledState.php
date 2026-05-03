<?php

namespace App\States;

use App\Jobs\SendOrderStatusEmail;
use App\Models\Order;

class CanceledState extends AbstractOrderState
{
    protected array $allowedTransitions = [];

    protected function afterTransition(Order $order, string $newStatus): void
    {
        SendOrderStatusEmail::dispatch($order->user->email, $order);
    }
}
