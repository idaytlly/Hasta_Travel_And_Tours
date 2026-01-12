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
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Api\Staff\StaffReportsController;
use App\Http\Controllers\Api\Staff\StaffDashboardController;
use App\Http\Controllers\Api\Staff\StaffBookingsController;
use App\Http\Controllers\Api\Staff\StaffDeliveryController;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [GuestController::class, 'home'])->name('guest.home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
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

    Route::post('/vouchers/validate', [BookingController::class, 'validateVoucher'])->name('vouchers.validate');
    
    // === ADD THESE TWO MISSING LINES ===
    Route::get('/bookings/{id}/payment', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/bookings/{id}/payment', [BookingController::class, 'storePayment'])->name('bookings.payment.store');
    // ===================================

    Route::get('/bookings/{id}/inspection/{type}', [BookingController::class, 'inspection'])->name('bookings.inspection');
    Route::post('/bookings/{id}/inspection/{type}', [BookingController::class, 'storeInspection'])->name('bookings.inspection.store');

    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
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
Route::get('/test-auth', function () {
    \Log::info('=== TEST AUTH ROUTE ===');
    \Log::info('Session ID: ' . session()->getId());
    \Log::info('Customer auth: ' . (Auth::guard('customer')->check() ? 'YES' : 'NO'));
    \Log::info('Staff auth: ' . (Auth::guard('staff')->check() ? 'YES' : 'NO'));
    
    if (Auth::guard('staff')->check()) {
        $staff = Auth::guard('staff')->user();
        return response()->json([
            'status' => 'authenticated',
            'guard' => 'staff',
            'user' => [
                'id' => $staff->staff_id,
                'name' => $staff->name,
                'role' => $staff->role,
            ],
            'session_id' => session()->getId()
        ]);
    }
    
    if (Auth::guard('customer')->check()) {
        return response()->json([
            'status' => 'authenticated',
            'guard' => 'customer'
        ]);
    }
    
    return response()->json([
        'status' => 'not_authenticated'
    ]);
});
/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/home', [CustomerController::class, 'home'])->name('customer.home');
    
    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'showProfile'])->name('customer.profile');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
    
    // Rewards
    Route::get('/customer/reward', [CustomerController::class, 'rewards'])->name('customer.reward');
});

// Vehicles (accessible to all)
Route::resource('vehicles', VehicleController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| Staff Portal Routes (Traditional Views)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('staff.dashboard.index');
    })->name('dashboard');
    
    // Bookings Views
    Route::get('/bookings', function () {
        return view('staff.bookings.index');
    })->name('bookings');
    
    // Reports Views
    Route::get('/reports', function () {
        return view('staff.reports.index');
    })->name('reports');
    
    // Delivery Views
    Route::get('/delivery', function () {
        return view('staff.delivery.index');
    })->name('delivery');
    
    // Vehicles Views
    Route::get('/vehicles', function () {
        return view('staff.vehicles.index');
    })->name('vehicles');
    
    // Customers Views
    Route::get('/customers', function () {
        return view('staff.customers.index');
    })->name('customers');

    Route::get('/staff-management', function () {
        // Check if user is admin
        if (Auth::guard('staff')->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        return view('staff.staff-management.index');
    })->name('staff-management');
});

/*
|--------------------------------------------------------------------------
| Staff API Routes (For AJAX Data Fetching)
|--------------------------------------------------------------------------
*/

Route::prefix('api/staff')->middleware(['auth:staff'])->group(function () {
    
    // Dashboard API
    Route::get('/dashboard/data', [StaffDashboardController::class, 'getDashboardData']);
    Route::get('/dashboard/stats', [StaffDashboardController::class, 'getStats']);
    Route::get('/dashboard/recent-bookings', [StaffDashboardController::class, 'getRecentBookings']);
    Route::get('/dashboard/schedule', [StaffDashboardController::class, 'getTodaySchedule']);
    
    // Reports API
    Route::get('/reports/data', [StaffReportsController::class, 'getReportData']);
    Route::post('/reports/export', [StaffReportsController::class, 'exportReport']);
    
    // Bookings API
    Route::get('/bookings', [StaffBookingsController::class, 'getBookings']);
    Route::get('/bookings/{id}', [StaffBookingsController::class, 'getBookingDetails']);
    Route::post('/bookings/{id}/approve', [StaffBookingsController::class, 'approveBooking']);
    Route::post('/bookings/{id}/verify', [StaffBookingsController::class, 'verifyBooking']);
    Route::post('/bookings/{id}/cancel', [StaffBookingsController::class, 'cancelBooking']);
    
    // Delivery/Pickup API (for runners)
    Route::get('/delivery/tasks', [StaffDeliveryController::class, 'getTasks']);
    Route::get('/delivery/tasks/{id}', [StaffDeliveryController::class, 'getTaskDetails']);
    Route::post('/delivery/tasks/{id}/start', [StaffDeliveryController::class, 'startTask']);
    Route::post('/delivery/tasks/{id}/complete', [StaffDeliveryController::class, 'completeTask']);
    Route::get('/delivery/commission', [StaffDeliveryController::class, 'getCommission']);
});