<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Staff;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::all();
        $staff = Staff::all();
        $staffIds = $staff->pluck('staff_id')->toArray();
        
        foreach ($bookings as $booking) {
            $paymentStatus = match($booking->booking_status) {
                'completed' => 'paid',
                'cancelled' => rand(0, 1) ? 'refunded' : 'failed',
                'active' => 'partially_paid',
                'confirmed' => rand(0, 1) ? 'paid' : 'partially_paid',
                default => 'pending',
            };
            
            $deposit = $booking->total_price * 0.3;
            $remaining = $paymentStatus === 'partially_paid' ? $booking->total_price - $deposit : 0;
            
            $payment = [
                'payment_id' => 'PAY' . substr($booking->booking_id, 4),
                'booking_id' => $booking->booking_id,
                'amount' => $booking->total_price,
                'payment_status' => $paymentStatus,
                'deposit' => $deposit,
                'remaining_payment' => $remaining,
            ];
            
            if ($paymentStatus !== 'pending') {
                $payment['payment_date'] = Carbon::now()->subDays(rand(1, 5));
                $payment['payment_proof'] = 'payment_proof_' . $booking->booking_id . '.jpg';
                $payment['payment_method'] = ['cash', 'card', 'online'][array_rand([0, 1, 2])];
                $payment['transaction_id'] = 'TXN' . rand(100000, 999999);
                
                if ($paymentStatus === 'paid') {
                    $payment['verified_by_staff'] = $staffIds[array_rand($staffIds)];
                    $payment['verified_at'] = Carbon::now()->subDays(rand(1, 3));
                }
                
                if ($paymentStatus === 'refunded') {
                    $payment['refunded_by_staff'] = $staffIds[array_rand($staffIds)];
                    $payment['refunded_at'] = Carbon::now()->subDays(rand(1, 2));
                }
            }
            
            Payment::create($payment);
        }
    }
}