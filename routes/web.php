<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use App\Models\Booking;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');

// Keep these for general access or remove if moving strictly into the booking flow
Route::post('/receipt', function () { return view('receipt'); })->name('receipt');

/*
|--------------------------------------------------------------------------
| Guest Routes (Only for non-authenticated users)
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
| Authenticated Routes (Only for logged in users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard - redirects based on usertype
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Payment and Receipt Routes
    
    // ⭐ ADD THIS - Voucher validation (AJAX)
    Route::post('/validate-voucher', [BookingController::class, 'validateVoucher'])->name('voucher.validate');
    
    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Booking Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/cars/{id}/book', [BookingController::class, 'create'])->name('create');


        // --- ADDED THIS LINE ---
        // This handles the "Pay Now" button by receiving the form data and showing the payment page
        Route::post('/payment-summary', [BookingController::class, 'processToPayment'])->name('payment-summary');
        
        Route::post('/', [BookingController::class, 'store'])->name('store');

        
        // --- ADDED THIS LINE ---
        // This handles the "Pay Now" button by receiving the form data and showing the payment page
        Route::post('/payment-summary', [BookingController::class, 'processToPayment'])->name('payment-summary');
        
        Route::post('/', [BookingController::class, 'store'])->name('store');

        
        // --- ADDED THIS LINE ---
        // This handles the "Pay Now" button by receiving the form data and showing the payment page
        Route::post('/payment-summary', [BookingController::class, 'processToPayment'])->name('payment-summary');
        
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');
        Route::get('/{reference}', [BookingController::class, 'show'])->name('show');
        Route::get('/{reference}/pending', [BookingController::class, 'pending'])->name('pending');
        Route::patch('/{reference}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('/payment-summary', [BookingController::class, 'processToPayment'])->name('payment-summary');
    });
    
    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{reference}', [BookingController::class, 'paymentPage'])->name('page');
        Route::get('/{reference}/summary', [BookingController::class, 'paymentSummary'])->name('summary');
        Route::get('/{reference}/pay', [BookingController::class, 'paymentPage'])->name('page');
        Route::post('/{reference}/process', [BookingController::class, 'processPayment'])->name('process');
        Route::get('/{reference}/receipt', [BookingController::class, 'receipt'])->name('receipt');
    });
});

/*
|--------------------------------------------------------------------------
| Staff Routes (Requires usertype: staff or admin)
|--------------------------------------------------------------------------
*/
Route::prefix('staff')->name('staff.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized');
        }
        return view('staff.dashboard');
    })->name('dashboard');
    
    // Car management routes
    Route::get('/cars', [CarController::class, 'staffIndex'])->name('cars.index');
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
    
    // Booking Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', function() {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return app(BookingController::class)->staffIndex();
        })->name('index');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return view('staff.reports.index');
        })->name('index');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Requires usertype: admin only)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AdminController;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    // Bookings Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminController::class, 'bookings'])->name('index');
        Route::get('/{id}', [AdminController::class, 'showBooking'])->name('show');
        Route::patch('/{id}/status', [AdminController::class, 'updateStatus'])->name('updateStatus');  // ← Changed to PATCH
        Route::delete('/{id}', [AdminController::class, 'deleteBooking'])->name('delete');
    });
    
    // Cars Management
    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/', [AdminController::class, 'cars'])->name('index');
        Route::get('/create', [AdminController::class, 'createCar'])->name('create');
        Route::post('/', [AdminController::class, 'storeCar'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'editCar'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'updateCar'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroyCar'])->name('destroy');
    });
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/{id}', [AdminController::class, 'showUser'])->name('show');
        Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('delete');
    });
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
