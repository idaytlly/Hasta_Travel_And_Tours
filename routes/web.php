<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;

// ==================== GUEST ROUTES ====================
Route::get('/', function () {
    return view('home');
})->name('guest.home');

// Authentication routes (Laravel Fortify usually handles these)
Route::middleware(['guest:web'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// ==================== CUSTOMER ROUTES ====================
Route::middleware(['auth:customer'])->group(function () {
    // Customer Bookings
    Route::prefix('customer/bookings')->name('customer.bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/payment', [BookingController::class, 'makePayment'])->name('payment');
        Route::get('/{id}/check-availability', [BookingController::class, 'checkAvailability'])->name('check-availability');
    });
});

// ==================== STAFF ROUTES ====================
Route::middleware(['staff.auth'])->prefix('staff')->name('staff.')->group(function () {
    // Bookings Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [StaffBookingController::class, 'index'])->name('index');
        Route::get('/create', [StaffBookingController::class, 'create'])->name('create');
        Route::post('/', [StaffBookingController::class, 'store'])->name('store');
        Route::get('/{id}', [StaffBookingController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StaffBookingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StaffBookingController::class, 'update'])->name('update');
        Route::delete('/{id}', [StaffBookingController::class, 'destroy'])->name('destroy');
        
        // Booking Actions
        Route::post('/{id}/approve', [StaffBookingController::class, 'approve'])->name('approve');
        Route::post('/{id}/cancel', [StaffBookingController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/mark-active', [StaffBookingController::class, 'markActive'])->name('mark-active');
        Route::post('/{id}/mark-completed', [StaffBookingController::class, 'markCompleted'])->name('mark-completed');
        Route::post('/{id}/extend', [StaffBookingController::class, 'extend'])->name('extend');
        
        // Reports
        Route::get('/late-returns', [StaffBookingController::class, 'lateReturns'])->name('late-returns');
        Route::get('/export', [StaffBookingController::class, 'export'])->name('export');
    });
});

// ==================== CUSTOMER DASHBOARD ====================
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
});

// ==================== STAFF DASHBOARD ====================
Route::middleware(['staff.auth'])->group(function () {
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
});