<?php

namespace App\States;

use App\Jobs\SendOrderStatusEmail;
use App\Models\Order;

class ProcessingState extends AbstractOrderState
{
    protected array $allowedTransitions = ['shipped', 'canceled'];

    protected function afterTransitions(Order $order, string $newStatus): void
    {
        SendOrderStatusEmail::dispatch($order->user->email, $order);
    }
}
