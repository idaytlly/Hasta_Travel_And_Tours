<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Call your car and voucher seeders only
        $this->call([
            CarSeeder::class,
            VoucherSeeder::class,
        ]);
        
        // If you need a test user, create it without profile_photo_path:
        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}