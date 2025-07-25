<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Services\FCMService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Temporarily disabled FCM service due to missing credentials file
        // $this->app->singleton(FCMService::class, function ($app) {
        //     return new FCMService();
        // });

        // $this->app->singleton(\App\Services\NotificationService::class, function ($app) {
        //     return new \App\Services\NotificationService($app->make(FCMService::class));
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // تعيين pagination view مخصص
        Paginator::defaultView('admin.layouts.pagination');
    }
}
