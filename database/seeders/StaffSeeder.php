<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('staff')->insert([
            'name' => 'Staff Test',
            'email' => 'staff@utm.my',
            'phone_no' => '0198765432',
            'password' => Hash::make('staff1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
