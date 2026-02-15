<?php

namespace App\Observers;

use App\Exceptions\InsufficientQuantityException;
use App\Exceptions\NotFoundException;
use App\Models\OrderItems;
use App\Models\Product;

class OrderItemsObserver
{
    /**
     * Handle the OrderItems "created" event.
     */
    public function created(OrderItems $orderItems): void
    {
        $product = Product::find($orderItems->product_id);

        if(!$product) {
            throw new NotFoundException('Product');
        }

        if ($product->stock < $orderItems->quantity) {
            throw new InsufficientQuantityException($product->id, $orderItems->quantity, $product->stock);
        }

        $product->decrement('stock', $orderItems->quantity);
    }

    /**
     * Handle the OrderItems "updated" event.
     */
    public function updated(OrderItems $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItems "deleted" event.
     */
    public function deleted(OrderItems $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItems "restored" event.
     */
    public function restored(OrderItems $orderItems): void
    {
        //
    }

    /**
     * Handle the OrderItems "force deleted" event.
     */
    public function forceDeleted(OrderItems $orderItems): void
    {
        //
    }
}
