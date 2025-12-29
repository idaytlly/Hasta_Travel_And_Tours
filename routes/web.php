<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Updated to support brand/category filtering via query strings
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
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

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

// Note: Ensure your 'can:admin-access' gate is defined in App\Providers\AuthServiceProvider
Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Booking Management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');
    
    // Crucial: This name must match what we used in the Approve/Reject forms
    Route::patch('/bookings/{id}/status', [AdminController::class, 'updateStatus'])->name('bookings.updateStatus');

    // Vehicle Management
    Route::get('/cars', [AdminController::class, 'cars'])->name('cars.index');
    Route::get('/cars/create', [AdminController::class, 'createCar'])->name('cars.create');
    Route::post('/cars', [AdminController::class, 'storeCar'])->name('cars.store');
    Route::delete('/cars/{id}', [AdminController::class, 'destroyCar'])->name('cars.destroy');
});

/*
|--------------------------------------------------------------------------
| Staff Routes (For Testing/Development)
|--------------------------------------------------------------------------
*/

Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', function () { return view('staff.dashboard'); })->name('dashboard');
    Route::get('/cars', [CarController::class, 'staffIndex'])->name('cars');
<<<<<<< HEAD
    Route::get('/bookings', [BookingController::class, 'staffIndex'])->name('bookings');
});
=======
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

>>>>>>> 224da8c17cf9994db51d002af56fec17bf104c09
