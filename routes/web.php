<?php
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CustomerProfileController;

//Guest Routes
Route::get('/', function () {
    return view('guest.home');
})->name('guest.home');

// Authentication Routes
// Registration
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login
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
?>