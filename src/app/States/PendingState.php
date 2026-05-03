<?php

namespace App\States;

use App\Jobs\SendOrderStatusEmail;
use App\Models\Order;

class PendingState extends AbstractOrderState
{
    public const STATUS = 'pending';

    protected array $allowedTransitions = ['processing', 'canceled'];

    protected function afterTransition(Order $order, string $newStatus): void
    {
        SendOrderStatusEmail::dispatch($order->user->email, $order);
    }
}
