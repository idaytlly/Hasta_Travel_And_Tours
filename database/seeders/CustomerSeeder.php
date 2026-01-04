<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            'name' => 'Siti Nur Iman Nadhirah',
            'email' => 'imancyan1709@gmail.com',
            'ic' => '050917100026',
            'phone_no' => '0123456789',
            'matricNum' => A24CS0153,   // nullable column
            'password' => bcrypt('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
