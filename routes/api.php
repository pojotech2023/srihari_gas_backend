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
Route::post('/transactions/callback', [OrderController::class, 'transactionCallback']);
Route::get('/customers/{id}/orders', [OrderController::class, 'customerOrders']);
Route::post('/deliveryman/login', [DeliverymanController::class, 'login']);

// Protected Routes (Require API Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    
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
    Route::post('/checkout', [OrderController::class, 'checkout']);

    // Deliveryman Actions
    Route::get('/deliveryman/orders', [DeliverymanController::class, 'listAssignedOrders']);
    Route::get('/deliveryman/orders/{id}', [DeliverymanController::class, 'showOrderDetail']);
    Route::post('/deliveryman/orders/{id}/status', [DeliverymanController::class, 'updateOrderStatus']);
});
