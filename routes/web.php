<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\Staff\AuthController;

Route::get('/', function () {
    return view('guest.home');
})->name('guest.home');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login - Single page for both
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Customer Routes
Route::get('/customer/home', function () {
    return view('customer.home');
})->name('customer.home');

// Customer Profile
Route::get('/profile', [CustomerProfileController::class, 'showProfile'])->name('customer.profile');
Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
Route::post('/profile/update', [CustomerProfileController::class, 'update'])->name('customer.profile.update');

// ========== STAFF ROUTES ==========

// Staff login page (public)
Route::get('/staff/login', function () {
    // If already logged in, redirect to dashboard
    if (request()->cookie('staff_authenticated') === 'true') {
        return redirect()->route('staff.dashboard');
    }
    return view('staff.auth.login');
})->name('staff.login');

// Staff login POST route
Route::post('/staff/login', function () {
    // Simple staff authentication
    $username = request()->input('username');
    $password = request()->input('password');
    
    if ($username === 'staff' && $password === 'password123') {
        // Set cookie and redirect to dashboard
        return redirect()->route('staff.dashboard')
            ->cookie('staff_authenticated', 'true', 480); // 8 hours
    }
    
    // If invalid credentials
    return back()->with('error', 'Invalid staff credentials');
})->name('staff.login.submit');

// Staff logout
Route::get('/staff/logout', function () {
    return response()
        ->redirectToRoute('staff.login')
        ->cookie(cookie()->forget('staff_authenticated'));
})->name('staff.logout');

// ========== PROTECTED STAFF ROUTES (using middleware) ==========
// CORRECT WAY: Use 'staff.auth' middleware which is registered in bootstrap/app.php
Route::middleware('staff.auth')->prefix('staff')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard.index');
    })->name('staff.dashboard');
    
    Route::get('/bookings', function () {
        return view('staff.bookings.index');
    })->name('staff.bookings');
    
    Route::get('/reports', function () {
        return view('staff.reports.index');
    })->name('staff.reports');
    
    Route::get('/delivery', function () {
        return view('staff.delivery.index');
    })->name('staff.delivery');
    
    Route::get('/vehicles', function () {
        return view('staff.vehicles.index');
    })->name('staff.vehicles');
    
    Route::get('/customers', function () {
        return view('staff.customers.index');
    })->name('staff.customers');
});