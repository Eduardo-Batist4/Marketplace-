<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Repositories\AddressRepositories;
use App\Repositories\CouponRepositories;
use App\Repositories\OrdersRepositories;
use App\Repositories\ProductRepositories;
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
        
        $cartItems = $user->cart->cartItems; // Get all items of the cart 
        if($cartItems->isEmpty()) {
            throw new HttpException(400, 'No items in the cart');
        }


        DB::beginTransaction();
        try {
            /*
                Create Order
            */
            $order = $this->ordersRepositories->createOrder($data);
    
            $cartItems = $user->cart->cartItems; // Get all items of the cart 
            $totalCart = 0; // Total cart price

            /*
                Scroll through all the items in the cart
            */
            foreach ($cartItems as $item) {

                /*
                    Check if the products are discounted
                */
                $productPrice = $this->productHasDiscount($item->product_id, $item->unit_price);

                $totalCart += $productPrice * $item->quantity;

                
                $orderItems = $this->orderItemService->createOrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $productPrice
                ]);

            }

            /*
                Updated total_amount from order table
            */
            $order->update(['total_amount' => $this->applyCoupon($totalCart, $data['coupon_id'])]);
            DB::commit();
            return $order;

        } catch (\Exception $error) {
            DB::rollback();
            throw $error;            
        }

    }

    public function productHasDiscount(int $id, int $unit_price)
    {
        $product = $this->productService->getProduct($id);

        if (!$product->discounts) {
            return $product->unit_price;
        }

        $totalDiscount = 0;
        foreach ($product->discounts as $discount) {
            $totalDiscount += $discount->discount_percentage; 
        }

        return  $unit_price - ($unit_price * ($totalDiscount / 100));
    }

    public function applyCoupon(int $totalPrice, int $id) 
    {
        $coupon = $this->couponRepositories->getCoupon($id);

        if ($coupon) {
            return $totalPrice - ($totalPrice * ($coupon->discount_percentage / 100));
        }
        return $totalPrice;
    }
}
