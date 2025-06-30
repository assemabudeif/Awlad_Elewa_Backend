<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('settings', [App\Http\Controllers\Api\SettingController::class, 'index']);


// Products
Route::get('products', [App\Http\Controllers\Api\ProductController::class, 'index']);
Route::get('products/{id}', [App\Http\Controllers\Api\ProductController::class, 'show']);

// Categories
Route::get('categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
Route::get('categories/{id}', [App\Http\Controllers\Api\CategoryController::class, 'show']);

// Banners
Route::get('banners', [App\Http\Controllers\Api\BannerController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    // Orders
    Route::apiResource('orders', App\Http\Controllers\Api\OrderController::class);

    // Repair Orders
    Route::apiResource('repair-orders', App\Http\Controllers\Api\RepairOrderController::class);

    // Profile
    Route::get('profile', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::put('profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::delete('profile', [App\Http\Controllers\Api\ProfileController::class, 'destroy']);

    // Cart
    Route::get('cart', [App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post('cart/add', [App\Http\Controllers\Api\CartController::class, 'addToCart']);
    Route::put('cart/update', [App\Http\Controllers\Api\CartController::class, 'updateCart']);
    Route::delete('cart/remove', [App\Http\Controllers\Api\CartController::class, 'removeFromCart']);
    Route::post('cart/clear', [App\Http\Controllers\Api\CartController::class, 'clearCart']);
});

// Auth
Route::post('auth/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('auth/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('auth/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
Route::middleware('auth:sanctum')->post('auth/reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('auth/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
