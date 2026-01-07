<?php

namespace App\Providers;

use App\Models\Customer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register view
        Fortify::registerView(fn() => view('register'));

        // Login view
        Fortify::loginView(fn() => view('login'));

        // Create customer
        Fortify::createUsersUsing(function (array $input) {
            return Customer::create([
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
        });

        // Redirect selepas register
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/customer/dashboard');
            }
        });

        // Redirect selepas login
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                return redirect('/customer/dashboard');
            }
        });
    }
}
?>