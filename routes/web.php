<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RepairOrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This is an admin-only system. All routes require admin authentication.
|
*/

// Redirect root to admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Password Reset Routes (Public)
Route::get('/password-reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/password-reset', [PasswordResetController::class, 'resetPassword'])->name('password.reset');

// Admin Routes - All require admin authentication
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Orders
    Route::resource('orders', OrderController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Repair Orders
    Route::resource('repair-orders', RepairOrderController::class);

    // Banners
    Route::resource('banners', BannerController::class);

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // Notifications
    Route::resource('notifications', NotificationController::class);
    Route::post('notifications/{notification}/send', [NotificationController::class, 'send'])->name('notifications.send');

    // Admin Management - Super Admin Only
    Route::resource('admin-management', AdminManagementController::class)->parameters([
        'admin-management' => 'admin'
    ])->middleware('admin');
});

require __DIR__ . '/auth.php';
