<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'customer_id' => 'CUS001',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Ahmad bin Ali',
                'ic_number' => '901234-56-7890',
                'phone_no' => '+60123456789',
                'ic_passport_image' => 'ahmad_ic_front.jpg',
                'license_image' => 'ahmad_license.jpg',
                'matric_card_image' => 'ahmad_matric.jpg',
                'matricNum' => 'A123456',
                'license_no' => 'DL1234567',
                'license_expiry' => '2030-12-31',
                'emergency_phoneNo' => '+60129876543',
                'emergency_name' => 'Ali bin Abu',
                'emergency_relationship' => 'Father',
            ],
            [
                'customer_id' => 'CUS002',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Siti Nurhaliza',
                'ic_number' => '850505-10-1234',
                'phone_no' => '+60198765432',
                'ic_passport_image' => 'siti_ic_front.jpg',
                'license_image' => 'siti_license.jpg',
                'matric_card_image' => null,
                'matricNum' => null,
                'license_no' => 'DL7654321',
                'license_expiry' => '2029-11-30',
                'emergency_phoneNo' => '+60127788899',
                'emergency_name' => 'Hussein',
                'emergency_relationship' => 'Husband',
            ],
            [
                'customer_id' => 'CUS003',
                'email' => 'raj@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Raj Kumar',
                'ic_number' => '880808-08-8888',
                'phone_no' => '+60161234567',
                'ic_passport_image' => 'raj_passport.jpg',
                'license_image' => 'raj_license.jpg',
                'matric_card_image' => 'raj_matric.jpg',
                'matricNum' => 'B234567',
                'license_no' => 'DL8888888',
                'license_expiry' => '2031-06-30',
                'emergency_phoneNo' => '+60169998877',
                'emergency_name' => 'Priya',
                'emergency_relationship' => 'Sister',
            ],
            [
                'customer_id' => 'CUS004',
                'email' => 'lim@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Lim Wei Jie',
                'ic_number' => '920202-02-0202',
                'phone_no' => '+60172345678',
                'ic_passport_image' => 'lim_ic_front.jpg',
                'license_image' => 'lim_license.jpg',
                'matric_card_image' => 'lim_matric.jpg',
                'matricNum' => 'C345678',
                'license_no' => 'DL0202020',
                'license_expiry' => '2028-09-15',
                'emergency_phoneNo' => '+60165544332',
                'emergency_name' => 'Lim Ah Beng',
                'emergency_relationship' => 'Brother',
            ],
            [
                'customer_id' => 'CUS005',
                'email' => 'fatimah@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Fatimah bt Hassan',
                'ic_number' => '950505-05-5555',
                'phone_no' => '+60163456789',
                'ic_passport_image' => 'fatimah_ic_front.jpg',
                'license_image' => 'fatimah_license.jpg',
                'matric_card_image' => null,
                'matricNum' => null,
                'license_no' => 'DL5555555',
                'license_expiry' => '2030-03-20',
                'emergency_phoneNo' => '+60168877665',
                'emergency_name' => 'Hassan',
                'emergency_relationship' => 'Father',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['customer_id' => $customer['customer_id']],
                $customer
            );
        }
    }
}