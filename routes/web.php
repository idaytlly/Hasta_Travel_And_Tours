<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\Staff\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\Staff\VehicleController as StaffVehicleController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;
use App\Http\Controllers\Staff\ReportController;
use App\Http\Controllers\Staff\ReportExportController;
use App\Http\Controllers\Api\Staff\StaffReportsController;
use App\Http\Controllers\Api\Staff\StaffDashboardController;
use App\Http\Controllers\Api\Staff\StaffBookingsController;
use App\Http\Controllers\Api\Staff\StaffDeliveryController;
use App\Http\Controllers\Staff\StaffManagementController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('guest.home');
})->name('guest.home');

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
    
    // Vehicle Management
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', [StaffVehicleController::class, 'index'])->name('index');
        Route::get('/create', [StaffVehicleController::class, 'create'])->name('create');
        Route::post('/', [StaffVehicleController::class, 'store'])->name('store');
        Route::get('/{vehicle}', [StaffVehicleController::class, 'show'])->name('show');
        Route::get('/{vehicle}/edit', [StaffVehicleController::class, 'edit'])->name('edit');
        Route::put('/{vehicle}', [StaffVehicleController::class, 'update'])->name('update');
        Route::delete('/{vehicle}', [StaffVehicleController::class, 'destroy'])->name('destroy');
    });
    
    // Customers Views
    Route::get('/customers', function () {
        return view('staff.customers.index');
    })->name('customers');

    // Staff Management Views
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
| Report Export Routes
|--------------------------------------------------------------------------
*/

Route::prefix('staff/reports')->middleware(['auth:staff'])->name('staff.reports.')->group(function () {
    // Report data API
    Route::get('/data', [StaffReportsController::class, 'getReportData'])->name('data');
    
    // Export routes
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/pdf', [ReportExportController::class, 'exportPdf'])->name('pdf');
        Route::get('/excel', [ReportExportController::class, 'exportExcel'])->name('excel');
        Route::get('/customer/{customer}/invoice', [ReportExportController::class, 'exportCustomerInvoice'])->name('customer.invoice');
        Route::get('/monthly/{year}/{month}', [ReportExportController::class, 'exportMonthlyReport'])->name('monthly');
    });
});

/*
|--------------------------------------------------------------------------
| Staff API Routes (For AJAX Data Fetching)
|--------------------------------------------------------------------------
*/

Route::prefix('api/staff')->middleware(['auth:staff'])->group(function () {
    // Staff Management API Routes
    Route::get('/staff', [StaffController::class, 'index'])->name('api.staff.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('api.staff.store');
    Route::post('/staff/{staff_id}', [StaffController::class, 'update']);
    Route::put('/staff/{staff_id}', [StaffController::class, 'update']);
    Route::delete('/staff/{staff_id}', [StaffController::class, 'destroy']);
    Route::put('/staff/{staff_id}/status', [StaffController::class, 'updateStatus']);
    Route::post('/staff/{staff_id}/reset-password', [StaffController::class, 'resetPassword']);

    // Dashboard API
    Route::get('/dashboard/data', [StaffDashboardController::class, 'getDashboardData']);
    Route::get('/dashboard/stats', [StaffDashboardController::class, 'getStats']);
    Route::get('/dashboard/recent-bookings', [StaffDashboardController::class, 'getRecentBookings']);
    Route::get('/dashboard/schedule', [StaffDashboardController::class, 'getTodaySchedule']);
    
    // Reports API
    Route::get('/reports/data', [StaffReportsController::class, 'getReportData']);
    Route::post('/reports/export', [StaffReportsController::class, 'exportReport']);
    
    // Bookings API - FIXED: ADDED MISSING ROUTES
    // Bookings API

    Route::get('/bookings', [StaffBookingsController::class, 'index'])->name('api.staff.bookings.index');
    Route::get('/bookings/{id}', [StaffBookingsController::class, 'show'])->name('api.staff.bookings.show');
    Route::post('/bookings/{id}/approve', [StaffBookingsController::class, 'approve'])->name('api.staff.bookings.approve');
    Route::post('/bookings/{id}/verify-payment', [StaffBookingsController::class, 'verifyPayment'])->name('api.staff.bookings.verify-payment');
    Route::post('/bookings/{id}/verify', [StaffBookingsController::class, 'verify'])->name('api.staff.bookings.verify');
    Route::post('/bookings/{id}/cancel', [StaffBookingsController::class, 'cancel'])->name('api.staff.bookings.cancel');
    Route::post('/bookings/{id}/complete', [StaffBookingsController::class, 'complete'])->name('api.staff.bookings.complete');
    Route::get('/bookings/export', [StaffBookingsController::class, 'export'])->name('api.staff.bookings.export');
        
    // Delivery/Pickup API (for runners)
    Route::get('/delivery/tasks', [StaffDeliveryController::class, 'getTasks']);
    Route::get('/delivery/tasks/{id}', [StaffDeliveryController::class, 'getTaskDetails']);
    Route::post('/delivery/tasks/{id}/start', [StaffDeliveryController::class, 'startTask']);
    Route::post('/delivery/tasks/{id}/complete', [StaffDeliveryController::class, 'completeTask']);
    Route::get('/delivery/commission', [StaffDeliveryController::class, 'getCommission']);

    // Customers API
    Route::get('/customers/data', [CustomerController::class, 'getCustomersData']);
    Route::get('/customers/stats', [CustomerController::class, 'getCustomerStats']);
});