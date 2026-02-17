<?php

namespace App\Services;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\NoItemsInCartException;
use App\Exceptions\NotFoundException;
use App\Jobs\SendOrderCreateEMail;
use App\Jobs\SendOrderStatusEmail;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function getAllOrder($user_id): Collection
    {
        return Order::where('user_id', $user_id)->with('orderItems.product')->get();
    }

    public function getAllOrderEveryone(): Collection
    {
        return Order::with(['user', 'orderItems.product'])->get();
    }

    public function createOrder(array $data, int $user_id): Order
    {
        $user = User::with(['address', 'cart.cartItems.product'])
            ->findOrFail($user_id);

        $data['user_id'] = $user->id;
        $data['status'] = config('app.order_status');

        $this->validateOrderCreation($user, $data);

        DB::beginTransaction();
        try {
            $orderData = $this->prepareOrderData($user, $data);
            $order = Order::create($orderData['order']);
            $this->createOrderItemsFromCart($order, $orderData['items']);

            $order->update(['total_amount' => $orderData['total_amount']]);

            DB::commit();

            $this->afterOrderCreated($user, $order);

            return $order->fresh();
        } catch (\Exception $error) {
            DB::rollback();
            throw $error;
        }
    }

    private function validateOrderCreation(User $user, array $data): void
    {
        $address = Address::where('user_id', $user->id)
            ->where('id', $data['address_id'])
            ->first();

        if (!$address) {
            throw new NotFoundException('Address', $data['address_id']);
        }

        if ($user->cart->cartItems->isEmpty()) {
            throw new NoItemsInCartException();
        }
    }

    private function prepareOrderData(User $user, array $data): array
    {
        $items = [];
        $totalCart = 0;

        foreach ($user->cart->cartItems as $item) {
            $productPrice = $this->productHasDiscount($item->product_id, $item->unit_price);
            $totalCart += $productPrice * $item->quantity;
            $items[] = [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $productPrice
            ];
        }

        $total_amount = $this->applyCoupon($totalCart, $data['coupon_id'] ?? null);

        return [
            'order' => array_merge($data, ['user_id' => $user->id]),
            'items' => $items,
            'total_amount' => number_format($total_amount, 2, ',', '.')
        ];
    }

    private function createOrderItemsFromCart(Order $order, array $items): void
    {
        foreach ($items as $item) {
            OrderItems::create(array_merge($item, ['order_id' => $order->id]));
        }
    }

    private function afterOrderCreated(User $user, Order $order): void
    {
        SendOrderCreateEMail::dispatch($user, $order);
        CartItem::where('cart_id', $user->cart->id)->delete();
    }

    private function productHasDiscount(int $id, int $unit_price): int
    {
        $product = Product::with('discounts')->findOrFail($id);

        if ($product->discounts->isEmpty()) {
            return $product->price;
        }

        $totalDiscount = $product->discounts->sum('discount_percentage');

        return $unit_price - ($unit_price * ($totalDiscount / 100));
    }

    private function applyCoupon(int $totalPrice, ?int $id): int
    {
        if (!$id) {
            return $totalPrice;
        }

        $coupon = Coupon::find($id);

        if (!$coupon) {
            return $totalPrice;
        }

        return $totalPrice - ($totalPrice * ($coupon->discount_percentage / 100));
    }

    public function updateStatus(array $data, int $id, int $user_id): Order
    {
        $user = User::findOrFail($user_id);

        if (!in_array($user->role, ['admin', 'moderator'])) {
            throw new AccessDeniedException();
        }

        $order = Order::findOrFail($id);
        $order->update($data);

        SendOrderStatusEmail::dispatch($user->email, $order);

        return $order->fresh();
    }

    public function deleteOrder(int $id): bool
    {
        $order = Order::findOrFail($id);

        return $order->delete();
    }
}
