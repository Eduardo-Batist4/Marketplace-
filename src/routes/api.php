<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Login and Register
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Product
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Feedback
Route::get('/feedbacks', [FeedbackController::class, 'index']);

Route::middleware('auth:api')->group(function () {

    // Switch role
    Route::put('/users/{id}/role', [RoleController::class, 'update'])->middleware('is_admin');

    // Users
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Update User Image
    Route::put('/users/{id}/update-image', [UserController::class, 'updateImage']);

    // Address
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);

    // Category
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store'])->middleware('is_admin');
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->middleware('is_admin');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('is_admin');

    // Product
    Route::post('/products', [ProductController::class, 'store'])->middleware('is_not_client');
    Route::put('/products/{id}', [ProductController::class, 'update'])->middleware('is_not_client');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('is_not_client');

    // Discount
    Route::get('/discounts', [DiscountController::class, 'index'])->middleware('is_admin');
    Route::post('/discounts', [DiscountController::class, 'store'])->middleware('is_admin');
    Route::get('/discounts/{id}', [DiscountController::class, 'show'])->middleware('is_admin');
    Route::put('/discounts/{id}', [DiscountController::class, 'update'])->middleware('is_admin');
    Route::delete('/discounts/{id}', [DiscountController::class, 'destroy'])->middleware('is_admin');

    // Coupon
    Route::get('/coupons', [CouponController::class, 'index'])->middleware('is_admin');
    Route::post('/coupons', [CouponController::class, 'store'])->middleware('is_admin');
    Route::get('/coupons/{id}', [CouponController::class, 'show'])->middleware('is_admin');
    Route::put('/coupons/{id}', [CouponController::class, 'update'])->middleware('is_admin');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->middleware('is_admin');

    // Cart Items
    Route::get('/cart_items', [CartItemController::class, 'index']);
    Route::post('/cart_items', [CartItemController::class, 'store']);
    Route::put('/cart_items/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart_items/{id}', [CartItemController::class, 'destroy']);

    // Order
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/everyone', [OrderController::class, 'getAllOrderEveryone'])->middleware('is_admin');
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}', [OrderController::class, 'update'])->middleware('is_admin');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->get('/who/is/user', function (Request $request) {
    return $request->user();
});
