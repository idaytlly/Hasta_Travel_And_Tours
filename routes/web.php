<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
//use App\Http\Controllers\StaffController;
//use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public car listing
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');

Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard'); // dashboard.blade.php
});

Route::get('/payment', function () {
    return view('payment');
});

Route::post('/receipt', function () {
    return view('receipt');
});


// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile edit/update routes
    //Route::get('/profile', UpdateProfileInformationForm::class)->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Booking routes
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/create/{car}', [BookingController::class, 'create'])->name('create');
        Route::post('/store/{car}', [BookingController::class, 'store'])->name('store');
    });

    // Staff and Admin routes...
});

// Staff routes - NO AUTHENTICATION (for testing)
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/cars', [CarController::class, 'staffIndex'])->name('cars');
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{id}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{id}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{id}', [CarController::class, 'destroy'])->name('cars.destroy');
});

Route::get('/staff/bookings', function () {
    return view('staff.manage-bookings'); 
});

  /*
// Staff Routes (add middleware for protection)
Route::middleware(['auth'])->group(function() {
    Route::get('/staff/cars', [CarController::class, 'staffIndex'])->name('staff.cars');
    Route::get('/staff/cars/create', [CarController::class, 'create'])->name('staff.cars.create');
    Route::post('/staff/cars', [CarController::class, 'store'])->name('staff.cars.store');
    Route::get('/staff/cars/{id}/edit', [CarController::class, 'edit'])->name('staff.cars.edit');
    Route::put('/staff/cars/{id}', [CarController::class, 'update'])->name('staff.cars.update');
    Route::delete('/staff/cars/{id}', [CarController::class, 'destroy'])->name('staff.cars.destroy');
});

  
    |--------------------------------------------------------------------------
    | Staff Routes
    |--------------------------------------------------------------------------
    
    Route::middleware('staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'index'])->name('dashboard');
        Route::get('/cars', [CarController::class, 'staffIndex'])->name('cars');
        Route::get('/bookings', [StaffController::class, 'bookings'])->name('bookings');
         Add car management routes (create, edit, delete) here
    });

    
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/home', [AdminController::class, 'index'])->name('home');
         Add admin management routes here
    });
    */

