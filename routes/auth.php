<?php

use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| This is an admin-only system. All authentication is for admins.
|
*/

// Admin Authentication Routes
Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AdminAuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:admin')
    ->name('logout');

// Admin-specific routes (aliases for consistency)
Route::get('/admin/login', [AdminAuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('admin.login');

Route::post('/admin/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:admin')
    ->name('admin.logout');
