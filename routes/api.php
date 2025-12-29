<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VoucherController;

// Car Routes
Route::prefix('cars')->group(function () {
    Route::get('/', [CarController::class, 'index']);
    Route::get('/brands', [CarController::class, 'getBrands']);
    Route::post('/', [CarController::class, 'store']);
    Route::get('/{id}', [CarController::class, 'show']);
    Route::put('/{id}', [CarController::class, 'update']);
    Route::patch('/{id}', [CarController::class, 'update']);
    Route::delete('/{id}', [CarController::class, 'destroy']);
    Route::post('/check-availability', [CarController::class, 'checkAvailability']);
    Route::patch('/{id}/toggle-availability', [CarController::class, 'toggleAvailability']);
});

// Booking Routes
Route::prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/', [BookingController::class, 'store']);
    Route::get('/{reference}', [BookingController::class, 'show']);
    Route::put('/{reference}', [BookingController::class, 'update']);
    Route::patch('/{reference}', [BookingController::class, 'update']);
    Route::patch('/{reference}/cancel', [BookingController::class, 'cancel']);
    Route::patch('/{reference}/status', [BookingController::class, 'updateStatus']);
    Route::patch('/{reference}/payment', [BookingController::class, 'updatePayment']);
});

// Voucher Routes
Route::prefix('vouchers')->group(function () {
    Route::get('/', [VoucherController::class, 'index']);
    Route::post('/validate', [VoucherController::class, 'validate']);
});