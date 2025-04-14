<?php

namespace App\Services;

use App\Jobs\SendOrderStatusEmail;
use App\Repositories\AddressRepositories;
use App\Repositories\CartItemRepositories;
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
        protected CouponRepositories $couponRepositories,
        protected CartItemRepositories $cartItemRepositories
    ) {}

    public function getAllOrder()
    {
        try {
            return $this->ordersRepositories->getAllOrder();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
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
            throw new HttpException(404, 'Address not found!');
        }
        
        $cartItems = $user->cart->cartItems; // Get all items of the cart 
        if($cartItems->isEmpty()) {
            throw new HttpException(404, 'No items in the cart');
        }

        DB::beginTransaction();
        try {
            /*
                Create Order
            */
            $order = $this->ordersRepositories->createOrder($data);

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

                $this->orderItemService->createOrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $productPrice
                ]);

            }

            /*
                Updated total_amount from order table
            */
            $total_amount = $this->applyCoupon($totalCart, $data['coupon_id'] ?? null);

            $order->update(['total_amount' => $total_amount]);

            DB::commit();
            
            $this->cartItemRepositories->deleteAllItems();
            return $order;
        } catch (\Exception $error) {
            DB::rollback();
            throw $error;            
        }

    }

    public function productHasDiscount(int $id, int $unit_price)
    {
        try {
            $product = $this->productService->getProduct($id);
    
            if (!$product->discounts) {
                return $product->unit_price;
            }
    
            $totalDiscount = 0;
            foreach ($product->discounts as $discount) {
                $totalDiscount += $discount->discount_percentage; 
            }
    
            return  $unit_price - ($unit_price * ($totalDiscount / 100));
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function applyCoupon(int $totalPrice, ?int $id) 
    {
        try {
            if (!$id) {
                return $totalPrice;
            }
    
            $coupon = $this->couponRepositories->getCoupon($id);
    
            if (!$coupon) {
                return $totalPrice;
            }
    
            return $totalPrice - ($totalPrice * ($coupon->discount_percentage / 100));
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateStatus(array $data, int $id, int $user_id)
    {
        try {
            if (!$this->userRepositories->userIsAdminOrModerator($user_id)) {
                throw new HttpException(403, 'Access denied.'); 
            }
            
            $user = $this->userRepositories->getUser($user_id);
            $order = $this->ordersRepositories->getOrder($id);
    
            SendOrderStatusEmail::dispatch($user->email, $order);
    
            return $this->ordersRepositories->updateOrder($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteOrder(int $id)
    {
        try {
            $this->ordersRepositories->getOrder($id);
    
            return $this->ordersRepositories->deleteOrder($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
