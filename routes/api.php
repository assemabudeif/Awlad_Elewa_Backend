<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RepairOrderController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Apply ForceJsonResponse to all API routes
Route::middleware(\App\Http\Middleware\ForceJsonResponse::class)->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    // Public routes
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });


    // Public data routes
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/categories/{category}/products', [CategoryController::class, 'products']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::post('/products/{product}/images', [ProductController::class, 'addImages']);
    Route::delete('/products/{product}/images/{image}', [ProductController::class, 'removeImage']);
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/settings', [SettingController::class, 'index']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout']);

        // Profile routes
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);

        // Cart routes
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart', [CartController::class, 'addToCart']);
        Route::put('/cart/{id}', [CartController::class, 'updateCart']);
        Route::delete('/cart/{id}', [CartController::class, 'removeFromCart']);
        Route::delete('/cart', [CartController::class, 'clearCart']);

        // Wishlist routes
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist', [WishlistController::class, 'store']);
        Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);

        // Order routes
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);

        // Repair order routes
        Route::get('/repair-orders', [RepairOrderController::class, 'index']);
        Route::post('/repair-orders', [RepairOrderController::class, 'store']);
        Route::get('/repair-orders/{repairOrder}', [RepairOrderController::class, 'show']);

        // Notification routes
        Route::post('/fcm-token', [NotificationController::class, 'updateFcmToken']);
        Route::post('/notifications/toggle', [NotificationController::class, 'toggleNotifications']);
        Route::get('/notifications/settings', [NotificationController::class, 'getSettings']);
        Route::get('/notifications', [NotificationController::class, 'getNotifications']);
        Route::post('/notifications/{notification_id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    });
}); // End ForceJsonResponse middleware group
