<?php

namespace App\Services;

use App\Jobs\SendOrderCreateEMail;
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

    public function getAllOrder($user_id)
    {
        try {
            return $this->ordersRepositories->getAllOrder($user_id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getAllOrderEveryone()
    {
        try {
            return $this->ordersRepositories->getAllOrderEveryone();
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createOrder(array $data, int $user_id)
    {
        $user = $this->userRepositories->getUser($user_id)->load('address');
        $data['user_id'] = $user->id;
        
        $address = $this->addressRepositories->getAddressWithUser($user->id, $data['address_id']);
        if (!$address) {
            throw new HttpException(404, 'Address not found!');
        }
        
        $cartItems = $user->cart->cartItems; 
        if($cartItems->isEmpty()) {
            throw new HttpException(404, 'No items in the cart');
        }

        DB::beginTransaction();
        try {
            $order = $this->ordersRepositories->createOrder($data);

            $totalCart = 0; 

            foreach ($cartItems as $item) {

                $productPrice = $this->productHasDiscount($item->product_id, $item->unit_price);

                $totalCart += $productPrice * $item->quantity;

                $this->orderItemService->createOrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $productPrice
                ]);

            }

            $total_amount = $this->applyCoupon($totalCart, $data['coupon_id'] ?? null);

            $order->update(['total_amount' => number_format($total_amount, 2, ',', '.')]);

            DB::commit();
            
            SendOrderCreateEMail::dispatch($user, $order);

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
    
            $order = $this->ordersRepositories->updateOrder($data, $id);
            
            SendOrderStatusEmail::dispatch($user->email, $order);
            return $order;
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
