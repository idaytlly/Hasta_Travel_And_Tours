<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Customer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    // 1. Admin gate
    Gate::define('admin-access', function ($user) {
        return $user->role === 'admin';
    });

    // 2. Share unread notifications with all staff views
    View::composer('layouts.staff', function ($view) {
        // Temporarily disabled - fix database prefix issue first
        $unreadNotifications = 0;
        $view->with('unreadNotifications', $unreadNotifications);
    });
}

}


