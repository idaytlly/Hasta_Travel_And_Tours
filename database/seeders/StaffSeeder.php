<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = [
            [
                'staff_id' => 'STF001',
                'name' => 'Admin User',
                'email' => 'admin@cashboard.com',
                'phone_no' => '+60123456789',
                'ic_number' => '800101-01-0101',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'staff_id' => 'STF002',
                'name' => 'John Delivery',
                'email' => 'john.delivery@cashboard.com',
                'phone_no' => '+60112233445',
                'ic_number' => '850202-02-0202',
                'password' => Hash::make('password'),
                'role' => 'runner',
                'is_active' => true,
            ],
            [
                'staff_id' => 'STF003',
                'name' => 'Sarah Pickup',
                'email' => 'sarah.pickup@cashboard.com',
                'phone_no' => '+60113344556',
                'ic_number' => '870303-03-0303',
                'password' => Hash::make('password'),
                'role' => 'runner',
                'is_active' => true,
            ],
            [
                'staff_id' => 'STF004',
                'name' => 'Michael Manager',
                'email' => 'michael@cashboard.com',
                'phone_no' => '+60114455667',
                'ic_number' => '890404-04-0404',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ],
            [
                'staff_id' => 'STF005',
                'name' => 'Lisa Support',
                'email' => 'lisa@cashboard.com',
                'phone_no' => '+60115566778',
                'ic_number' => '910505-05-0505',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ],
        ];

        foreach ($staffMembers as $staff) {
            Staff::updateOrCreate(
                ['staff_id' => $staff['staff_id']], // Search by primary key
                $staff // Update or create with these values
            );
        }
    }
}