<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');

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
    Route::post('/payment', function (Request $request) {
        return view('payment', ['bookingData' => $request->all()]);
    })->name('payment.show');
    
    
    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/delete', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Booking Routes
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/cars/{id}/book', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');
        Route::get('/{reference}', [BookingController::class, 'show'])->name('show');
        Route::get('/{reference}/pending', [BookingController::class, 'pending'])->name('pending');
        Route::patch('/{reference}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::post('/payment-summary', [BookingController::class, 'processToPayment'])->name('payment-summary');
    });
    
    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
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
    
    // Car management
    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/', function() {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return app(CarController::class)->staffIndex();
        })->name('index');
        
        Route::get('/create', function() {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return app(CarController::class)->create();
        })->name('create');
        
        Route::post('/', function() {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return app(CarController::class)->store(request());
        })->name('store');
        
        Route::get('/{id}/edit', function($id) {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return app(CarController::class)->edit($id);
        })->name('edit');
        
        Route::patch('/{id}', function($id) {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return app(CarController::class)->update(request(), $id);
        })->name('update');
        
        Route::delete('/{id}', function($id) {
            if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return app(CarController::class)->destroy($id);
        })->name('destroy');
    });
    
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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->usertype !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Bookings Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', function () {
            if (auth()->user()->usertype !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return view('admin.bookings.index');
        })->name('index');
    });
    
    // Cars Management
    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/', function () {
            if (auth()->user()->usertype !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return view('admin.cars.index');
        })->name('index');
    });
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', function () {
            if (auth()->user()->usertype !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return view('admin.users.index');
        })->name('index');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            if (auth()->user()->usertype !== 'admin') {
                abort(403, 'Unauthorized');
            }
            return view('admin.settings.index');
        })->name('index');
    });
});