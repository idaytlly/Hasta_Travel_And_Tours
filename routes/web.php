<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;
use App\Http\Controllers\Staff\CarController as StaffCarController;
use App\Http\Controllers\Staff\InspectionController as StaffInspectionController;
use App\Http\Controllers\Staff\NotificationController as StaffNotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // User Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/bookings', [ProfileController::class, 'bookings'])->name('bookings');
    });
    
    // Booking Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/create/{car}', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::put('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });
});

/*
|--------------------------------------------------------------------------
| Staff & Admin Routes (Unified)
|--------------------------------------------------------------------------
| Both staff and admin users access this area
| Use role checks in controllers/views for admin-only features
*/

Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [StaffBookingController::class, 'dashboard'])->name('dashboard');
    
    // Bookings Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [StaffBookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [StaffBookingController::class, 'show'])->name('show');
        Route::put('/{booking}/status', [StaffBookingController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{booking}/confirm', [StaffBookingController::class, 'confirm'])->name('confirm');
        Route::patch('/{booking}/approve', [StaffBookingController::class, 'approve'])->name('approve');
        Route::patch('/{booking}/complete', [StaffBookingController::class, 'complete'])->name('complete');
        Route::post('/{booking}/cancel', [StaffBookingController::class, 'cancel'])->name('cancel');        Route::post('/{booking}/notes', [StaffBookingController::class, 'addNote'])->name('addNote');
    });

    // Cars Management
    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/', [StaffCarController::class, 'index'])->name('index');
        Route::get('/{car}', [StaffCarController::class, 'show'])->name('show');
        Route::put('/{car}/availability', [StaffCarController::class, 'updateAvailability'])->name('updateAvailability');
        Route::post('/{car}/maintenance', [StaffCarController::class, 'addMaintenance'])->name('addMaintenance');
        
        // Admin-only car management (checked in controller)
        Route::get('/create', [StaffCarController::class, 'create'])->name('create');
        Route::post('/', [StaffCarController::class, 'store'])->name('store');
        Route::get('/{car}/edit', [StaffCarController::class, 'edit'])->name('edit');
        Route::put('/{car}', [StaffCarController::class, 'update'])->name('update');
        Route::delete('/{car}', [StaffCarController::class, 'destroy'])->name('destroy');
    });
    
    // Inspections
    Route::prefix('inspections')->name('inspections.')->group(function () {
        Route::get('/', [StaffInspectionController::class, 'index'])->name('index');
        Route::get('/create/{booking}', [StaffInspectionController::class, 'create'])->name('create');
        Route::post('/', [StaffInspectionController::class, 'store'])->name('store');
        Route::get('/{inspection}', [StaffInspectionController::class, 'show'])->name('show');
        Route::get('/{inspection}/edit', [StaffInspectionController::class, 'edit'])->name('edit');
        Route::put('/{inspection}', [StaffInspectionController::class, 'update'])->name('update');
    });
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [StaffNotificationController::class, 'index'])->name('index');
        Route::put('/{notification}/read', [StaffNotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::put('/read-all', [StaffNotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{notification}', [StaffNotificationController::class, 'destroy'])->name('destroy');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [StaffNotificationController::class, 'profile'])->name('profile');
        Route::put('/profile', [StaffNotificationController::class, 'updateProfile'])->name('updateProfile');
        Route::put('/password', [StaffNotificationController::class, 'updatePassword'])->name('updatePassword');
    });
    
    // Admin-Only Routes (checked in controller)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [StaffUserController::class, 'index'])->name('index');
        Route::get('/{user}', [StaffUserController::class, 'show'])->name('show');
        Route::put('/{user}', [StaffUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [StaffUserController::class, 'destroy'])->name('destroy');
    });
    
    // Reports (Admin-only, checked in controller)
    Route::get('/reports', [StaffBookingController::class, 'reports'])->name('reports');
});
