<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class RunnerSeeder extends Seeder
{
    public function run(): void
    {
        $runners = [
            [
                'staff_id' => 'STR001',
                'name' => 'Tan Mei Ling',
                'email' => 'tan.meiling@carrental.com',
                'phone_no' => '+60116677889',
                'password' => Hash::make('password123'),
                'ic_number' => '890925-05-2345',
                'role' => 'runner',
                'is_active' => true,
            ],
            [
                'staff_id' => 'STR002',
                'name' => 'Ali Bin Abu',
                'email' => 'ali.abu@carrental.com',
                'phone_no' => '+60117788990',
                'password' => Hash::make('password123'),
                'ic_number' => '930408-12-6789',
                'role' => 'runner',
                'is_active' => true,
            ],
        ];

        foreach ($runners as $r) {
            Staff::updateOrCreate(['staff_id' => $r['staff_id']], $r);
        }

        $this->command->info('Runner staff records ensured.');
    }
}
