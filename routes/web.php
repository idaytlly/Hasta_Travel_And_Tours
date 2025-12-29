<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');

Route::get('/payment', function () { return view('payment'); })->name('payment');
Route::post('/receipt', function () { return view('receipt'); })->name('receipt');

/*
|--------------------------------------------------------------------------
| Guest Routes (Login & Register)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Booking Group
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');
        Route::get('/cars/{id}/book', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        
        Route::get('/{reference}', [BookingController::class, 'show'])->name('show');
        Route::get('/{reference}/pending', [BookingController::class, 'pending'])->name('pending');
        Route::get('/{reference}/confirmation', [BookingController::class, 'confirmation'])->name('confirmation');
        
        Route::put('/{reference}', [BookingController::class, 'update'])->name('update');
        Route::patch('/{reference}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });

    Route::post('/vouchers/validate', [VoucherController::class, 'validate'])->name('vouchers.validate');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Management)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Admin Booking Management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::patch('/bookings/{id}/status', [AdminController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Admin Vehicle Management
    Route::get('/cars', [AdminController::class, 'cars'])->name('cars.index');
    Route::get('/cars/create', [AdminController::class, 'createCar'])->name('cars.create');
    Route::post('/cars', [AdminController::class, 'storeCar'])->name('cars.store');
    Route::delete('/cars/{id}', [AdminController::class, 'destroyCar'])->name('cars.destroy');
});

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () { return view('staff.dashboard'); })->name('dashboard');
    
    // Staff Car Management
    Route::get('/cars', [CarController::class, 'staffIndex'])->name('cars');
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');

    Route::get('/cars/{id}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{id}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');
    
    // Staff Booking Management
    Route::get('/bookings', [BookingController::class, 'staffIndex'])->name('bookings');
});
