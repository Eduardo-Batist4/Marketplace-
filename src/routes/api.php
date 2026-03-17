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
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Category
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Product
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Feedback
Route::get('/feedbacks/{id}', [FeedbackController::class, 'show']);

// Reset Password
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

// Refresh Token
Route::post('/refreshToken', [AuthController::class, 'updateAccessToken']);

Route::middleware('auth:api')->group(function () {

    // Users
    Route::get('/users', [UserController::class, 'me']);
    Route::put('/users', [UserController::class, 'update']);
    Route::delete('/users', [UserController::class, 'destroy']);
    Route::put('/users/update-image', [UserController::class, 'updateImage']);

    // Address
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);


    Route::middleware(['is_admin'])->group(function () {
        // Switch role
        Route::put('/users/{id}/role', [RoleController::class, 'update']);

        // Users
        Route::get('/users/{id}', [UserController::class, 'show']);

        // Category
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // Discount
        Route::get('/discounts', [DiscountController::class, 'index']);
        Route::post('/discounts', [DiscountController::class, 'store']);
        Route::get('/discounts/{id}', [DiscountController::class, 'show']);
        Route::put('/discounts/{id}', [DiscountController::class, 'update']);
        Route::delete('/discounts/{id}', [DiscountController::class, 'destroy']);

        // Coupon
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::post('/coupons', [CouponController::class, 'store']);
        Route::get('/coupons/{id}', [CouponController::class, 'show']);
        Route::put('/coupons/{id}', [CouponController::class, 'update']);
        Route::delete('/coupons/{id}', [CouponController::class, 'destroy']);

        // Order
        Route::get('/orders/everyone', [OrderController::class, 'getAllOrderEveryone']);
        Route::put('/orders/{id}', [OrderController::class, 'update']);
    });

    Route::middleware(['is_admin_or_mod'])->group(function () {
        // Product
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // Feedback
        Route::delete('/feedbacks/{id}', [FeedbackController::class, 'destroy'])->middleware('is_admin_or_mod');
    });

    // Cart Items
    Route::get('/cart_items', [CartItemController::class, 'index']);
    Route::post('/cart_items', [CartItemController::class, 'store']);
    Route::put('/cart_items/{id}', [CartItemController::class, 'update']);
    Route::delete('/cart_items/{id}', [CartItemController::class, 'destroy']);

    // Order
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    // Feedback
    Route::post('/feedbacks', [FeedbackController::class, 'store']);
    Route::put('/feedbacks/{id}', [FeedbackController::class, 'update']);
});

Route::middleware('auth:sanctum')->get('/who/is/user', function (Request $request) {
    return $request->user();
});
