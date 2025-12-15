<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Welcome page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes (register/login/logout) provided by Laravel Breeze / Jetstream
Route::middleware('auth')->group(function () {

    // General user dashboard (after login)
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // Staff routes
    Route::middleware('staff')->group(function () {
        Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
        Route::get('/staff/cars', [CarController::class, 'staffIndex'])->name('staff.cars');
        Route::get('/staff/bookings', [StaffController::class, 'bookings'])->name('staff.bookings');

        // Staff car management
        Route::get('/staff/cars/create', [CarController::class, 'create'])->name('staff.cars.create');
        Route::post('/staff/cars', [CarController::class, 'store'])->name('staff.cars.store');
        Route::get('/staff/cars/{id}/edit', [CarController::class, 'edit'])->name('staff.cars.edit');
        Route::put('/staff/cars/{id}', [CarController::class, 'update'])->name('staff.cars.update');
        Route::delete('/staff/cars/{id}', [CarController::class, 'destroy'])->name('staff.cars.destroy');
    });

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/home', [AdminController::class, 'index'])->name('admin.home');
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Public Car Listing
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
