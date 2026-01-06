<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contactus');
Route::post('/contact-us', [ContactController::class, 'send'])->name('contactus.send');
Route::view('/about-us', 'aboutus')->name('aboutus');

// Guest routes
Route::middleware('guest:customer')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('auth.register');
    Route::post('/register', [RegisterController::class, 'register']);
    
});


Route::middleware('auth')->group(function () {
    // FIXED: Dashboard redirects based on usertype
    Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user(); // default web guard
    if ($user->usertype === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->usertype === 'staff') {
        return redirect()->route('staff.dashboard');
    } else {
        return app(HomeController::class)->dashboard();
    }
    })->name('dashboard');

    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/validate-voucher', [BookingController::class, 'validateVoucher'])->name('voucher.validate');
    
    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Bookings
    Route::get('/bookings/cars/{id}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/payment-summary', [BookingController::class, 'processToPayment'])->name('bookings.payment-summary');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');
    Route::get('/bookings/{reference}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{reference}/pending', [BookingController::class, 'pending'])->name('bookings.pending');
    Route::patch('/bookings/{reference}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    
    // Payment
    Route::get('/payment/{reference}', [BookingController::class, 'paymentPage'])->name('payment.page');
    Route::get('/payment/{reference}/summary', [BookingController::class, 'paymentSummary'])->name('payment.summary');
    Route::post('/payment/{reference}/process', [BookingController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{reference}/receipt', [BookingController::class, 'receipt'])->name('payment.receipt');
});

// Staff Routes - WITH ROLE CHECK
Route::prefix('staff')->name('staff.')->middleware('auth')->group(function () {
    // Check if user is staff or admin
    if (auth()->check() && !in_array(auth()->user()->usertype, ['staff', 'admin'])) {
        abort(403, 'Unauthorized access. Staff or admin privileges required.');
    }
    
    Route::view('/dashboard', 'staff.dashboard')->name('dashboard');
    
    Route::get('/cars', [CarController::class, 'staffIndex'])->name('cars.index');
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
    
    Route::get('/bookings', [BookingController::class, 'staffIndex'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'staffShow'])->name('bookings.show');
    Route::patch('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'staffCancel'])->name('bookings.cancel');
    
    Route::view('/reports', 'staff.reports.index')->name('reports.index');

    Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', function () {
        return view('staff.notifications.index');
    })->name('index');
    
    Route::put('/{notification}/read', function ($notification) {
        // Mark as read logic here
        return redirect()->back();
    })->name('markAsRead');
    });

    // Settings routes (also missing)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', function () {
            return view('staff.settings.profile');
        })->name('profile');
    });
});

// Admin Routes - WITH ROLE CHECK
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Check if user is admin
    if (auth()->check() && auth()->user()->usertype !== 'admin') {
        abort(403, 'Unauthorized access. Admin privileges required.');
    }
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{id}', [AdminController::class, 'showBooking'])->name('bookings.show');
    Route::patch('/bookings/{id}/status', [AdminController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking'])->name('bookings.delete');
    
    Route::get('/cars', [AdminController::class, 'cars'])->name('cars.index');
    Route::get('/cars/create', [AdminController::class, 'createCar'])->name('cars.create');
    Route::post('/cars', [AdminController::class, 'storeCar'])->name('cars.store');
    Route::get('/cars/{id}/edit', [AdminController::class, 'editCar'])->name('cars.edit');
    Route::put('/cars/{id}', [AdminController::class, 'updateCar'])->name('cars.update');
    Route::delete('/cars/{id}', [AdminController::class, 'destroyCar'])->name('cars.destroy');
    
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});