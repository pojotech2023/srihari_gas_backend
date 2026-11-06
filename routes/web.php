<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DeliverymanController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Marketing Website Routes
Route::get('/', [SiteController::class, 'home'])->name('site.home');
Route::get('/about', [SiteController::class, 'about'])->name('site.about');
Route::get('/services', [SiteController::class, 'services'])->name('site.services');
Route::get('/contact', [ContactController::class, 'show'])->name('site.contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('site.contact.submit');

// Admin Auth Routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Web Admin Dashboard Routes (Protected by AdminMiddleware)
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Real-time Notification check
    Route::get('/latest-order-id', function () {
        $latestOrder = \App\Models\Order::with('user')->latest()->first();
        return response()->json([
            'id' => $latestOrder ? $latestOrder->id : 0,
            'customer' => $latestOrder ? $latestOrder->user->name : 'N/A',
            'total' => $latestOrder ? number_format($latestOrder->total_price, 2) : '0.00'
        ]);
    })->name('latest-order-id');
    
    // Customer Management
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    
    // Product CRUD Management
    Route::resource('products', ProductController::class);
    
    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/assign', [OrderController::class, 'assignDeliveryman'])->name('orders.assign');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    
    // Deliveryman Management
    Route::get('/deliverymen', [DeliverymanController::class, 'index'])->name('deliverymen.index');
    Route::post('/deliverymen', [DeliverymanController::class, 'store'])->name('deliverymen.store');
    Route::get('/deliverymen/{id}/edit', [DeliverymanController::class, 'edit'])->name('deliverymen.edit');
    Route::put('/deliverymen/{id}', [DeliverymanController::class, 'update'])->name('deliverymen.update');
    Route::delete('/deliverymen/{id}', [DeliverymanController::class, 'destroy'])->name('deliverymen.destroy');
});
