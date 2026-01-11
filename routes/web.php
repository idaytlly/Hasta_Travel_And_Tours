<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Staff\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;

Route::get('/', function () {
    return view('guest.home');
})->name('guest.home');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login - Single page for both
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Customer Routes
Route::get('/customer/home', [CustomerController::class, 'home'])->name('customer.home');

// Customer Profile
Route::get('/profile', [CustomerProfileController::class, 'showProfile'])->name('customer.profile');
Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
Route::put('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');

Route::resource('vehicles', VehicleController::class)->only(['index', 'show']);

// Customer Booking
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/vehicles/{plate_no}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

//Rewards
Route::get('/customer/reward', [CustomerController::class, 'rewards'])->name('customer.reward');

// Staff Booking Routes
Route::middleware(['staff.auth'])->prefix('staff')->group(function () {
    // Bookings
    Route::get('/bookings', [Staff\BookingController::class, 'index'])->name('staff.bookings.index');
    Route::get('/bookings/create', [Staff\BookingController::class, 'create'])->name('staff.bookings.create');
    Route::post('/bookings', [Staff\BookingController::class, 'store'])->name('staff.bookings.store');
    Route::get('/bookings/{id}', [Staff\BookingController::class, 'show'])->name('staff.bookings.show');
    Route::get('/bookings/{id}/edit', [Staff\BookingController::class, 'edit'])->name('staff.bookings.edit');
    Route::put('/bookings/{id}', [Staff\BookingController::class, 'update'])->name('staff.bookings.update');
    Route::delete('/bookings/{id}', [Staff\BookingController::class, 'destroy'])->name('staff.bookings.destroy');
    
    // Booking Actions
    Route::post('/bookings/{id}/approve', [Staff\BookingController::class, 'approve'])->name('staff.bookings.approve');
    Route::post('/bookings/{id}/cancel', [Staff\BookingController::class, 'cancel'])->name('staff.bookings.cancel');
    Route::post('/bookings/{id}/mark-active', [Staff\BookingController::class, 'markAsActive'])->name('staff.bookings.mark-active');
    Route::post('/bookings/{id}/mark-returned', [Staff\BookingController::class, 'markAsReturned'])->name('staff.bookings.mark-returned');
    Route::post('/bookings/{id}/approve-late-charges', [Staff\BookingController::class, 'approveLateCharges'])->name('staff.bookings.approve-late-charges');
    Route::post('/bookings/{id}/extend', [Staff\BookingController::class, 'extendBooking'])->name('staff.bookings.extend');
    
    // Reports
    Route::get('/bookings/late-returns', [Staff\BookingController::class, 'lateReturns'])->name('staff.bookings.late-returns');
    Route::get('/bookings/export', [Staff\BookingController::class, 'export'])->name('staff.bookings.export');
});

?>