<?php

namespace App\Services;

use App\Repositories\AddressRepositories;
use App\Repositories\OrderItemRepositories;
use App\Repositories\OrdersRepositories;
use App\Repositories\UserRepositories;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderService
{

    public function __construct(
        protected OrdersRepositories $ordersRepositories,
        protected UserRepositories $userRepositories,
        protected AddressRepositories $addressRepositories,
        protected OrderItemRepositories $orderItemRepositories,
        protected OrderItemService $orderItemService,
        protected ProductService $productService,
    ) {}

    public function getAllOrder()
    {
        return $this->ordersRepositories->getAllOrder();
    }

    public function createOrder(array $data, int $user_id)
    {
        $user = $this->userRepositories->getUser($user_id)->load('address');
        $data['user_id'] = $user->id;

        $address = $this->addressRepositories->getAddressWithUser($user->id, $data['address_id']);
        if (!$address) {
            throw new HttpException(400, 'Address not found!');
        }
        
        $order = $this->ordersRepositories->createOrder($data);
        
        $cartItems = $user->cart->cartItems; 
        
        $totalCart = 0;
        $orderItemList = [];
        DB::beginTransaction();
        try {
            foreach ($cartItems as $item) {
                $valueWithDiscount = $this->productHasDiscount($item->product_id, $item->unit_price);
                
                if (!$valueWithDiscount) {
                    $totalCart += $item->unit_price * $item->quantity;
                }
                $totalCart += $valueWithDiscount * $item->quantity;

                
                $orderItems = $this->orderItemService->createOrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $valueWithDiscount
                ]);

                $orderItemList[] = $orderItems;
            }

            $order->update(['total_amount' => $totalCart]);
            DB::commit();
        } catch (\Exception $error) {
            DB::rollback();
            throw $error;
        }
        return $cartItems;
    }

    public function productHasDiscount(int $id, int $unit_price)
    {
        $product = $this->productService->getProduct($id);

        if (!$product->discounts) {
            return;
        }

        $totalDiscount = 0;
        foreach ($product->discounts as $discount) {
            $totalDiscount += $discount->discount_percentage; 
        }

        return  $unit_price - ($unit_price * ($totalDiscount / 100));
    }
}
