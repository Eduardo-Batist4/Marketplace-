<?php

namespace App\Services;

use App\Models\Coupon;
use App\Repositories\AddressRepositories;
use App\Repositories\CouponRepositories;
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
        protected OrderItemService $orderItemService,
        protected ProductService $productService,
        protected CouponRepositories $couponRepositories
    ) {}

    public function getAllOrder()
    {
        return $this->ordersRepositories->getAllOrder();
    }

    public function createOrder(array $data, int $user_id)
    {
        /* 
            Get the user by token
        */
        $user = $this->userRepositories->getUser($user_id)->load('address');
        $data['user_id'] = $user->id;
        
        /* 
            Checks if the address belongs to the logged-in user
        */
        $address = $this->addressRepositories->getAddressWithUser($user->id, $data['address_id']);
        if (!$address) {
            throw new HttpException(400, 'Address not found!');
        }
        
        /*
            Create Order
        */
        $order = $this->ordersRepositories->createOrder($data);


        $cartItems = $user->cart->cartItems; // Get all items of the cart 
        $totalCart = 0; // Total cart price


        DB::beginTransaction();
        try {

            /*
                Scroll through all the items in the cart
            */
            foreach ($cartItems as $item) {

                /*
                    Check if the products are discounted
                */
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

            }

            /*
                Updated total_amount from order table
            */
            $order->update(['total_amount' => $totalCart]);
            DB::commit();

        } catch (\Exception $error) {
            DB::rollback();
            throw $error;            
        }

        return $orderItems;
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

    public function orderHasCoupon(int $id) 
    {
        $coupon = $this->couponRepositories->getCoupon($id);

        return $coupon->discount_percentage;
    }
}
