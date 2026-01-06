<?php

return [

    'defaults' => [
        'guard' => 'web', // default guard for Laravel
        'passwords' => 'users',
    ],

    'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'customers',  // default guard uses customers table
    ],
    'customer' => [
        'driver' => 'session',
        'provider' => 'customers',
    ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'staff' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff::class,
        ],

        'customers' => [ // <- provider for your Customer model
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],
    ],

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
        ],

        'staff' => [
            'provider' => 'staff',
            'table' => 'password_resets',
            'expire' => 60,
        ],

        'customers' => [
            'provider' => 'customers',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],
];
