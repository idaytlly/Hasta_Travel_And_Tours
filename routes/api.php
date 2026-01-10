<?php

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/bookings/{booking}/verify', function (Request $request, Booking $booking) {
    $request->validate([
        'staff_id' => 'required|string',
        'password' => 'required|string',
    ]);
    
    // Use the verify method from Booking model
    $result = $booking->verify($request->staff_id, $request->password);
    
    return response()->json($result);
});