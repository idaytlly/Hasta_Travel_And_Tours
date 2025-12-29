@extends('layouts.app')

@section('title', 'Booking Confirmation - HASTA')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Success Header -->
            <div class="bg-green-600 text-white p-8 text-center">
                <div class="text-6xl mb-4">✓</div>
                <h1 class="text-3xl font-bold mb-2">Booking Confirmed!</h1>
                <p class="text-green-100">Your booking has been successfully created</p>
            </div>

            <!-- Booking Details -->
            <div class="p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Booking Reference</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-3xl font-mono font-bold text-red-600">{{ $booking->booking_reference }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Car</p>
                        <p class="font-semibold">{{ $booking->car->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Duration</p>
                        <p class="font-semibold">{{ $booking->duration }} days</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pickup Date</p>
                        <p class="font-semibold">{{ $booking->pickup_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Return Date</p>
                        <p class="font-semibold">{{ $booking->return_date->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Base Price</span>
                        <span class="font-semibold">RM{{ number_format($booking->base_price, 2) }}</span>
                    </div>
                    @if($booking->discount_amount > 0)
                        <div class="flex justify-between items-center mb-4 text-green-600">
                            <span>Discount ({{ $booking->voucher }})</span>
                            <span class="font-semibold">-RM{{ number_format($booking->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Deposit (10%)</span>
                        <span class="font-semibold">RM{{ number_format($booking->deposit_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xl font-bold border-t pt-4">
                        <span>Total</span>
                        <span class="text-red-600">RM{{ number_format($booking->total_price, 2) }}</span>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <a href="{{ route('bookings.show', $booking->booking_reference) }}"
                       class="flex-1 bg-red-600 text-white text-center py-3 rounded-lg hover:bg-red-700 transition font-semibold">
                        View Booking
                    </a>
                    <a href="{{ route('cars.index') }}"
                       class="flex-1 bg-gray-200 text-gray-800 text-center py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
                        Browse More Cars
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
