<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DeliverymanController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'forgotPassword']);
Route::post('/check-mobile', [AuthController::class, 'checkMobile']);
Route::post('/customer/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/customer/reset-password', [AuthController::class, 'forgotPassword']);
Route::post('/customer/check-mobile', [AuthController::class, 'checkMobile']);

Route::post('/transactions/callback', [OrderController::class, 'transactionCallback']);
Route::get('/customers/{id}/orders', [OrderController::class, 'customerOrders']);
Route::get('/orders/{id}', [OrderController::class, 'showOrder']);

Route::post('/deliveryman/login', [DeliverymanController::class, 'login']);
Route::post('/deliveryman/register', [DeliverymanController::class, 'register']);
Route::post('/deliveryman/signup', [DeliverymanController::class, 'register']);
Route::post('/deliveryman/forgot-password', [DeliverymanController::class, 'forgotPassword']);
Route::post('/deliveryman/reset-password', [DeliverymanController::class, 'forgotPassword']);
Route::post('/deliveryman/check-mobile', [DeliverymanController::class, 'checkMobile']);

// Protected Routes (Require API Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/customer/fcm-token', [AuthController::class, 'updateFcmToken']);
    Route::post('/fcm-token', [AuthController::class, 'updateFcmToken']);
    
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    
    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    
    // Addresses
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'listOrders']);
    Route::get('/orders/{id}', [OrderController::class, 'showOrder']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('/orders/cancel', [OrderController::class, 'cancelOrder']);
    Route::post('/customer/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);

    // Deliveryman Actions
    Route::get('/deliveryman/orders', [DeliverymanController::class, 'listAssignedOrders']);
    Route::get('/deliveryman/available-orders', [DeliverymanController::class, 'availableOrders']);
    Route::post('/deliveryman/orders/{id}/claim', [DeliverymanController::class, 'claimOrder']);
    Route::get('/deliveryman/orders/{id}', [DeliverymanController::class, 'showOrderDetail']);
    Route::post('/deliveryman/orders/{id}/status', [DeliverymanController::class, 'updateOrderStatus']);
    Route::post('/deliveryman/orders/{id}/payment-status', [DeliverymanController::class, 'updatePaymentStatus']);
    Route::post('/deliveryman/fcm-token', [DeliverymanController::class, 'updateFcmToken']);
    Route::post('/deliveryman/vehicle-details', [DeliverymanController::class, 'updateVehicleDetails']);
    Route::post('/deliveryman/profile-update', [DeliverymanController::class, 'updateProfile']);
});
