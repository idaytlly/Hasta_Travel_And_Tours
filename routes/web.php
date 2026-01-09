<?php
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthenticatedSessionController;

//Guest
Route::get('/', function () {
    return view('guest.home');
})->name('guest.home');

// Registration
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/customer/home', function () {
    return view('customer.home');
})->name('customer.home');

?>