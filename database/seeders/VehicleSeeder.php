<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Insert Customer first (required for bookings)
        $customer = [
            'customer_id' => 'CUS0001',
            'name' => 'John Doe',
            'ic_number' => '990101011234',
            'email' => 'john.doe@example.com',
            'phone_no' => '0123456789',
            'password' => bcrypt('password123'),
            'ic_passport_image' => 'customer_docs/ic_sample.jpg',
            'license_image' => 'customer_docs/license_sample.jpg',
            'license_no' => 'D1234567',
            'license_expiry' => $now->copy()->addYears(2)->toDateString(),
            'emergency_phoneNo' => '0198765432',
            'emergency_name' => 'Jane Doe',
            'emergency_relationship' => 'Spouse',
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('customers')->insert($customer);

        // Insert Vouchers (voucherAmount is stored as PERCENTAGE)
        $vouchers = [
            // Active vouchers - Small discounts
            [
                'voucher_id' => 'VCH001',
                'voucherCode' => 'WELCOME10',
                'voucherAmount' => 10, // 10% off
                'used_count' => 0,
                'expiryDate' => $now->copy()->addMonths(3)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'voucher_id' => 'VCH002',
                'voucherCode' => 'WEEKEND15',
                'voucherAmount' => 15, // 15% off
                'used_count' => 3,
                'expiryDate' => $now->copy()->addMonths(1)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'voucher_id' => 'VCH003',
                'voucherCode' => 'STUDENT5',
                'voucherAmount' => 5, // 5% off
                'used_count' => 12,
                'expiryDate' => $now->copy()->addMonths(6)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'voucher_id' => 'VCH004',
                'voucherCode' => 'LONGTERM25',
                'voucherAmount' => 25, // 25% off
                'used_count' => 1,
                'expiryDate' => $now->copy()->addMonths(2)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'voucher_id' => 'VCH005',
                'voucherCode' => 'NEWYEAR2026',
                'voucherAmount' => 20, // 20% off
                'used_count' => 0,
                'expiryDate' => $now->copy()->addMonths(1)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'voucher_id' => 'VCH006',
                'voucherCode' => 'FIRSTRIDE10',
                'voucherAmount' => 10, // 10% off
                'used_count' => 5,
                'expiryDate' => $now->copy()->addMonths(4)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'voucher_id' => 'VCH007',
                'voucherCode' => 'LOYAL30',
                'voucherAmount' => 30, // 30% off
                'used_count' => 8,
                'expiryDate' => $now->copy()->addMonths(12)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'voucher_id' => 'VCH008',
                'voucherCode' => 'MEGA50',
                'voucherAmount' => 50, // 50% off - Big promotion!
                'used_count' => 2,
                'expiryDate' => $now->copy()->addWeeks(2)->toDateString(),
                'voucherStatus' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // Expired vouchers (for testing)
            [
                'voucher_id' => 'VCH009',
                'voucherCode' => 'XMAS2025',
                'voucherAmount' => 20, // 20% off
                'used_count' => 15,
                'expiryDate' => $now->copy()->subDays(5)->toDateString(),
                'voucherStatus' => 'expired',
                'created_at' => $now->copy()->subMonths(2),
                'updated_at' => $now->copy()->subDays(5),
            ],
            [
                'voucher_id' => 'VCH010',
                'voucherCode' => 'SUMMER2025',
                'voucherAmount' => 15, // 15% off
                'used_count' => 20,
                'expiryDate' => $now->copy()->subMonths(1)->toDateString(),
                'voucherStatus' => 'expired',
                'created_at' => $now->copy()->subMonths(4),
                'updated_at' => $now->copy()->subMonths(1),
            ],
            
            // Inactive vouchers (suspended/disabled)
            [
                'voucher_id' => 'VCH011',
                'voucherCode' => 'FLASH25',
                'voucherAmount' => 25, // 25% off
                'used_count' => 2,
                'expiryDate' => $now->copy()->addDays(15)->toDateString(),
                'voucherStatus' => 'inactive',
                'created_at' => $now->copy()->subDays(10),
                'updated_at' => $now->copy()->subDays(3),
            ],
        ];

        DB::table('voucher')->insert($vouchers);

        $vehicles = [
            [
                'plate_no' => 'MCP 6113',
                'name' => 'Perodua Axia (1st Gen)',
                'color' => 'White',
                'year' => 2018,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-12',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-29',
                'return_time' => '09:00 AM',
                'vehicle_type' => 'Car',
                'image' => 'car_images/axia1stgen.jpg',
                'front_image' => 'car_images/axia1stgen_front.jpg',
                'back_image' => 'car_images/axia1stgen_back.jpg',
                'left_image' => 'car_images/axia1stgen_left.jpg',
                'right_image' => 'car_images/axia1stgen_right.jpg',
                'interior1_image' => 'car_images/axia1stgen_interior1.jpg',
                'interior2_image' => 'car_images/axia1stgen_interior2.jpg',
                'price_perHour' => 35.00,
                'passengers' => 5,
                'distance_travelled' => 12000,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'VC 6522',
                'name' => 'Perodua Myvi (2nd Gen)',
                'color' => 'Silver',
                'year' => 2012,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-12',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-15',
                'return_time' => '09:00 AM',
                'vehicle_type' => 'Car',
                'image' => 'car_images/myvi2ndgen.jpg',
                'front_image' => 'car_images/myvi2ndgen_front.jpg',
                'back_image' => 'car_images/myvi2ndgen_back.jpg',
                'left_image' => 'car_images/myvi2ndgen_left.jpg',
                'right_image' => 'car_images/myvi2ndgen_right.jpg',
                'interior1_image' => '#',
                'interior2_image' => '#',
                'price_perHour' => 40.00,
                'passengers' => 5,
                'distance_travelled' => 15000,
                'availability_status' => 'unavailable',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'QRP 5205',
                'name' => 'Honda Dash 125',
                'vehicle_type' => 'Bike',
                'color' => 'Yellow',
                'year' => 2023,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-12',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-13',
                'return_time' => '09:00 AM',
                'image' => 'car_images/dash125.jpg',
                'price_perHour' => 10.00,
                'passengers' => 2,
                'distance_travelled' => 5000,
                'availability_status' => 'unavailable',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'JWD 9496',
                'name' => 'Honda Beat 110',
                'vehicle_type' => 'Bike',
                'color' => 'Black',
                'year' => 2023,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-12',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-13',
                'return_time' => '09:00 AM',
                'image' => 'car_images/beat110.jpg',
                'price_perHour' => 9.00,
                'passengers' => 2,
                'distance_travelled' => 3000,
                'availability_status' => 'unavailable',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'UTM 3365',
                'name' => 'Perodua Axia (2nd Gen)',
                'vehicle_type' => 'Car',
                'color' => 'Silver',
                'year' => 2024,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-12',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-29',
                'return_time' => '09:00 AM',
                'image' => 'car_images/axia2ndgen.jpg',
                'front_image' => 'car_images/axia2ndgen_front.jpg',
                'back_image' => 'car_images/axia2ndgen_back.jpg',
                'left_image' => 'car_images/axia2ndgen_left.jpg',
                'right_image' => 'car_images/axia2ndgen_right.jpg',
                'interior1_image' => 'car_images/axia2ndgen_interior1.jpg',
                'interior2_image' => 'car_images/axia2ndgen_interior2.jpg',
                'price_perHour' => 40.00,
                'passengers' => 5,
                'distance_travelled' => 10000,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'NDF 9903',
                'name' => 'Perodua Bezza (1st Gen)',
                'color' => 'White',
                'year' => 2019,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-15',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-16',
                'return_time' => '09:00 AM',
                'vehicle_type' => 'Car',
                'image' => 'car_images/bezza1stgen.jpg',
                'price_perHour' => 40.00,
                'passengers' => 5,
                'distance_travelled' => 12000,
                'availability_status' => 'unavailable',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'UTM 3655',
                'name' => 'Perodua Bezza (2nd Gen)',
                'vehicle_type' => 'Car',
                'color' => 'Black',
                'year' => 2023,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-12',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-29',
                'return_time' => '09:00 AM',
                'image' => 'car_images/bezza2ndgen.jpg',
                'front_image' => 'car_images/bezza2ndgen_front.jpg',
                'back_image' => 'car_images/bezza2ndgen_back.jpg',
                'left_image' => 'car_images/bezza2ndgen_left.jpg',
                'right_image' => 'car_images/bezza2ndgen_right.jpg',
                'interior1_image' => 'car_images/bezza2ndgen_interior1.jpg',
                'interior2_image' => 'car_images/bezza2ndgen_interior2.jpg',
                'price_perHour' => 50.00,
                'passengers' => 5,
                'distance_travelled' => 8000,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'JQU 1957',
                'name' => 'Perodua Axia (1st Gen)',
                'color' => 'Green',
                'year' => 2015,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-15',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-25',
                'return_time' => '09:00 AM',
                'vehicle_type' => 'Car',
                'image' => 'car_images/axia1stgen.jpg',
                'front_image' => 'car_images/axia1stgen_front2.jpg',
                'back_image' => 'car_images/axia1stgen_back2.jpg',
                'left_image' => 'car_images/axia1stgen_left2.jpg',
                'right_image' => 'car_images/axia1stgen_right2.jpg',
                'interior1_image' => 'car_images/axia1stgen_interior1sec.jpg',
                'interior2_image' => 'car_images/axia1stgen_interior2sec.jpg',
                'price_perHour' => 35.00,
                'passengers' => 5,
                'distance_travelled' => 8000,
                'availability_status' => 'unavailable',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'CEX 5224',
                'name' => 'Perodua Axia (2nd Gen)',
                'color' => 'Blue',
                'year' => 2024,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-15',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-25',
                'return_time' => '09:00 AM',
                'vehicle_type' => 'Car',
                'image' => 'car_images/axia2ndgen.jpg',
                'front_image' => 'car_images/axia2ndgen_front2.jpg',
                'back_image' => 'car_images/axia2ndgen_back2.jpg',
                'left_image' => 'car_images/axia2ndgen_left2.jpg',
                'right_image' => 'car_images/axia2ndgen_right2.jpg',
                'interior1_image' => 'car_images/axia2ndgen_interior1sec.jpg',
                'interior2_image' => 'car_images/axia2ndgen_interior2sec.jpg',
                'price_perHour' => 40.00,
                'passengers' => 5,
                'distance_travelled' => 10000,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'JPN 1416',
                'name' => 'Perodua Myvi (2nd Gen)',
                'color' => 'Purple',
                'year' => 2013,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-12',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-28',
                'return_time' => '09:00 AM',
                'vehicle_type' => 'Car',
                'image' => 'car_images/myvi2ndgen.jpg',
                'front_image' => 'car_images/myvi2ndgen_front2.jpg',
                'back_image' => 'car_images/myvi2ndgen_back2.jpg',
                'left_image' => 'car_images/myvi2ndgen_left2.jpg',
                'right_image' => 'car_images/myvi2ndgen_right2.jpg',
                'interior1_image' => 'car_images/myvi2ndgen_interior1.jpg',
                'interior2_image' => 'car_images/myvi2ndgen_interior2.jpg',
                'price_perHour' => 40.00,
                'passengers' => 5,
                'distance_travelled' => 15000,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'plate_no' => 'UTM 3057',
                'name' => 'Perodua Bezza (2nd Gen)',
                'color' => 'Red',
                'year' => 2025,
                'pickup_location' => json_encode(['Hasta Office','College','Faculty']),
                'pickup_date' => '2026-01-15',
                'pickup_time' => '09:00 AM',
                'return_date' => '2026-01-28',
                'return_time' => '09:00 AM',
                'vehicle_type' => 'Car',
                'image' => 'car_images/bezza2ndgen.jpg',
                'front_image' => 'car_images/bezza2ndgen_front2.jpg',
                'back_image' => 'car_images/bezza2ndgen_back2.jpg',
                'left_image' => 'car_images/bezza2ndgen_left2.jpg',
                'right_image' => 'car_images/bezza2ndgen_right2.jpg',
                'interior1_image' => 'car_images/bezza2ndgen_interior1sec.jpg',
                'interior2_image' => 'car_images/bezza2ndgen_interior2sec.jpg',
                'price_perHour' => 50.00,
                'passengers' => 5,
                'distance_travelled' => 8000,
                'availability_status' => 'available',
                'staff_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Ensure every record has the same keys
        $columns = [
            'plate_no','name','color','year','vehicle_type','image','front_image','back_image',
            'left_image','right_image','interior1_image','interior2_image','price_perHour',
            'passengers','distance_travelled','availability_status','staff_id','created_at','updated_at'
        ];

        foreach ($vehicles as &$v) {
            foreach ($columns as $col) {
                if (!array_key_exists($col, $v)) {
                    $v[$col] = null;
                }
            }
        }

        DB::table('vehicle')->insert($vehicles);

        // Insert Bookings (with RM 15 delivery fee where applicable)
        $bookings = [
            // Confirmed booking - ready for pickup inspection (NO DELIVERY - both HASTA office)
            [
                'booking_id' => 'BKG-' . time() . '-001',
                'customer_id' => 'CUS0001',
                'plate_no' => 'MCP 6113',
                'pickup_date' => $now->copy()->addDays(1)->toDateString(),
                'pickup_time' => '09:00:00',
                'pickup_location' => 'HASTA office',
                'pickup_details' => 'HASTA Office',
                'return_date' => $now->copy()->addDays(2)->toDateString(),
                'return_time' => '18:00:00',
                'dropoff_location' => 'HASTA office',
                'dropoff_details' => 'HASTA Office',
                'delivery_required' => false,
                'total_price' => 1155.00, // 33 hours × RM35 = 1155, no delivery fee
                'booking_status' => 'confirmed',
                'voucher_id' => null,
                'signature' => 'signatures/sample_signature_1.png',
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(1),
            ],
            
            // Confirmed booking - ready for pickup inspection (WITH DELIVERY)
            [
                'booking_id' => 'BKG-' . (time() + 1) . '-002',
                'customer_id' => 'CUS0001',
                'plate_no' => 'UTM 3365',
                'pickup_date' => $now->toDateString(),
                'pickup_time' => '10:00:00',
                'pickup_location' => 'Residential College',
                'pickup_details' => 'Kolej Tun Dr. Ismail',
                'return_date' => $now->copy()->addDays(1)->toDateString(),
                'return_time' => '17:00:00',
                'dropoff_location' => 'HASTA office',
                'dropoff_details' => 'HASTA Office',
                'delivery_required' => true,
                'total_price' => 1255.00, // 31 hours × RM40 = 1240 + RM15 delivery = 1255
                'booking_status' => 'confirmed',
                'voucher_id' => null,
                'signature' => 'signatures/sample_signature_2.png',
                'created_at' => $now->copy()->subDays(3),
                'updated_at' => $now->copy()->subDays(1),
            ],
            
            // Pending booking - waiting for payment (WITH DELIVERY + 25% VOUCHER)
            [
                'booking_id' => 'BKG-' . (time() + 2) . '-003',
                'customer_id' => 'CUS0001',
                'plate_no' => 'UTM 3655',
                'pickup_date' => $now->copy()->addDays(3)->toDateString(),
                'pickup_time' => '08:00:00',
                'pickup_location' => 'Faculty',
                'pickup_details' => 'Faculty of Computing',
                'return_date' => $now->copy()->addDays(4)->toDateString(),
                'return_time' => '20:00:00',
                'dropoff_location' => 'Faculty',
                'dropoff_details' => 'Faculty of Computing',
                'delivery_required' => true,
                'total_price' => 1365.00, // Rental: 36h × RM50 = 1800, Discount 25% = -450, Subtotal = 1350 + RM15 delivery = 1365
                'booking_status' => 'pending',
                'voucher_id' => 'VCH004', // LONGTERM25 voucher applied (25% off rental only)
                'signature' => 'signatures/sample_signature_3.png',
                'created_at' => $now->copy()->subHours(2),
                'updated_at' => $now->copy()->subHours(2),
            ],
            
            // Completed booking (NO DELIVERY + 10% VOUCHER)
            [
                'booking_id' => 'BKG-' . (time() + 3) . '-004',
                'customer_id' => 'CUS0001',
                'plate_no' => 'JPN 1416',
                'pickup_date' => $now->copy()->subDays(5)->toDateString(),
                'pickup_time' => '09:00:00',
                'pickup_location' => 'HASTA office',
                'pickup_details' => 'HASTA Office',
                'return_date' => $now->copy()->subDays(4)->toDateString(),
                'return_time' => '18:00:00',
                'dropoff_location' => 'HASTA office',
                'dropoff_details' => 'HASTA Office',
                'delivery_required' => false,
                'total_price' => 1188.00, // Rental: 33h × RM40 = 1320, Discount 10% = -132, Total = 1188 (no delivery)
                'booking_status' => 'completed',
                'voucher_id' => 'VCH006', // FIRSTRIDE10 voucher used (10% off)
                'signature' => 'signatures/sample_signature_4.png',
                'created_at' => $now->copy()->subDays(7),
                'updated_at' => $now->copy()->subDays(4),
            ],
            
            // Another confirmed booking (WITH DELIVERY - both locations need delivery)
            [
                'booking_id' => 'BKG-' . (time() + 4) . '-005',
                'customer_id' => 'CUS0001',
                'plate_no' => 'CEX 5224',
                'pickup_date' => $now->copy()->addDays(2)->toDateString(),
                'pickup_time' => '14:00:00',
                'pickup_location' => 'Residential College',
                'pickup_details' => 'Kolej Tun Razak',
                'return_date' => $now->copy()->addDays(3)->toDateString(),
                'return_time' => '14:00:00',
                'dropoff_location' => 'Residential College',
                'dropoff_details' => 'Kolej Tun Razak',
                'delivery_required' => true,
                'total_price' => 975.00, // Rental: 24h × RM40 = 960 + RM15 delivery = 975
                'booking_status' => 'confirmed',
                'voucher_id' => null,
                'signature' => 'signatures/sample_signature_5.png',
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subHours(12),
            ],
        ];

        DB::table('booking')->insert($bookings);
        
        // Insert payments for confirmed and active bookings
        $payments = [
            [
                'payment_id' => 'PAY-' . time() . '-001',
                'booking_id' => $bookings[0]['booking_id'],
                'amount' => 1155.00,
                'payment_method' => 'online',
                'payment_status' => 'paid',
                'payment_date' => $now->copy()->subDays(1),
                'payment_proof' => 'payment_proofs/payment_001.jpg',
                'deposit' => 0,
                'remaining_payment' => 0,
                'created_at' => $now->copy()->subDays(1),
                'updated_at' => $now->copy()->subDays(1),
            ],
            [
                'payment_id' => 'PAY-' . (time() + 1) . '-002',
                'booking_id' => $bookings[1]['booking_id'],
                'amount' => 1240.00,
                'payment_method' => 'online',
                'payment_status' => 'paid',
                'payment_date' => $now->copy()->subDays(2),
                'payment_proof' => 'payment_proofs/payment_002.jpg',
                'deposit' => 0,
                'remaining_payment' => 0,
                'created_at' => $now->copy()->subDays(2),
                'updated_at' => $now->copy()->subDays(2),
            ],
            [
                'payment_id' => 'PAY-' . (time() + 2) . '-004',
                'booking_id' => $bookings[3]['booking_id'],
                'amount' => 1290.00, // Reflects voucher discount
                'payment_method' => 'online',
                'payment_status' => 'paid',
                'payment_date' => $now->copy()->subDays(6),
                'payment_proof' => 'payment_proofs/payment_004.jpg',
                'deposit' => 0,
                'remaining_payment' => 0,
                'created_at' => $now->copy()->subDays(6),
                'updated_at' => $now->copy()->subDays(6),
            ],
            [
                'payment_id' => 'PAY-' . (time() + 3) . '-005',
                'booking_id' => $bookings[4]['booking_id'],
                'amount' => 960.00,
                'payment_method' => 'online',
                'payment_status' => 'paid',
                'payment_date' => $now->copy()->subHours(12),
                'payment_proof' => 'payment_proofs/payment_005.jpg',
                'deposit' => 0,
                'remaining_payment' => 0,
                'created_at' => $now->copy()->subHours(12),
                'updated_at' => $now->copy()->subHours(12),
            ],
        ];

        DB::table('payment')->insert($payments);
    }
}