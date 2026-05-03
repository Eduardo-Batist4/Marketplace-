<?php

namespace App\States;

use App\Exceptions\InvalidStateTransitionException;
use App\Models\Order;
use Override;

class AbstractOrderState implements OrderStateInterface
{
    protected array $allowedTransitions = [];

    #[Override]
    public function transitionTo(Order $order, string $newStatus): void
    {
        if (!in_array($newStatus, $this->allowedTransitions)) {
            throw new InvalidStateTransitionException($order->status, $newStatus);
        }

        $this->beforeTransition($order, $newStatus);
        $order->update(['status' => $newStatus]);
        $this->afterTransition($order, $newStatus);
    }

    protected function beforeTransition(Order $order, string $newStatus): void {}
    protected function afterTransition(Order $order, string $newStatus): void {}
}
