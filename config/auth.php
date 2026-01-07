<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => 'customer',      // default login = customer
        'passwords' => 'customers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [

        'customer' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staff',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [

        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],

        'staff' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff::class,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset
    |--------------------------------------------------------------------------
    */
    'passwords' => [

        'customers' => [
            'provider' => 'customers',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'staff' => [
            'provider' => 'staff',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

    ],

];
