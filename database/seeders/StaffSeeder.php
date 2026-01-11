<?php
// database/seeders/StaffSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing staff (except admins)
        Staff::where('role', '!=', 'admin')->delete();

        // Create admin account if not exists
        $adminExists = Staff::where('email', 'admin@carrental.com')->exists();
        if (!$adminExists) {
            Staff::create([
                'staff_id' => 'STFADM001',
                'name' => 'System Administrator',
                'email' => 'admin@carrental.com',
                'phone_no' => '+60123456789',
                'password' => Hash::make('admin123'),
                'ic_number' => '900101-01-1234', // ADDED
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create sample staff members
        $staffMembers = [
            [
                'staff_id' => 'STF001',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@carrental.com',
                'phone_no' => '+60112233445',
                'password' => Hash::make('password123'),
                'ic_number' => '880512-14-5678', // ADDED
                'role' => 'staff',
                'is_active' => true,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subDays(10),
            ],
            [
                'staff_id' => 'STF002',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@carrental.com',
                'phone_no' => '+60113344556',
                'password' => Hash::make('password123'),
                'ic_number' => '910623-08-9012', // ADDED
                'role' => 'staff',
                'is_active' => true,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subDays(5),
            ],
            [
                'staff_id' => 'STF003',
                'name' => 'Lee Chong Wei',
                'email' => 'lee.wei@carrental.com',
                'phone_no' => '+60114455667',
                'password' => Hash::make('password123'),
                'ic_number' => '870715-06-3456', // ADDED
                'role' => 'staff',
                'is_active' => true,
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subDays(2),
            ],
            [
                'staff_id' => 'STF004',
                'name' => 'Kumar Selvam',
                'email' => 'kumar.selvam@carrental.com',
                'phone_no' => '+60115566778',
                'password' => Hash::make('password123'),
                'ic_number' => '920812-10-7890', // ADDED
                'role' => 'staff',
                'is_active' => false,
                'created_at' => now()->subMonths(4),
                'updated_at' => now()->subMonths(1),
            ],
            [
                'staff_id' => 'STR001',
                'name' => 'Tan Mei Ling',
                'email' => 'tan.meiling@carrental.com',
                'phone_no' => '+60116677889',
                'password' => Hash::make('password123'),
                'ic_number' => '890925-05-2345', // ADDED
                'role' => 'runner',
                'is_active' => true,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subDays(3),
            ],
            [
                'staff_id' => 'STR002',
                'name' => 'Ali Bin Abu',
                'email' => 'ali.abu@carrental.com',
                'phone_no' => '+60117788990',
                'password' => Hash::make('password123'),
                'ic_number' => '930408-12-6789', // ADDED
                'role' => 'runner',
                'is_active' => true,
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($staffMembers as $staff) {
            Staff::create($staff);
        }

        $this->command->info('Staff accounts created successfully!');
        $this->command->info('Admin Login: admin@carrental.com / admin123');
        $this->command->info('Staff Login: ahmad.fauzi@carrental.com / password123');
        $this->command->info('Runner Login: tan.meiling@carrental.com / password123');
    }
}