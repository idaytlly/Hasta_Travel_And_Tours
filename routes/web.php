<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{id}', [CarController::class, 'show'])->name('cars.show');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contactus');
Route::post('/contact-us', [ContactController::class, 'send'])->name('contactus.send');
Route::view('/about-us', 'aboutus')->name('aboutus');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // FIXED: Dashboard redirects based on usertype
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->usertype === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->usertype === 'staff') {
            return redirect()->route('staff.dashboard');
        } else {
            // Show customer dashboard
            return app(HomeController::class)->dashboard();
        }
    })->name('dashboard');
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/validate-voucher', [BookingController::class, 'validateVoucher'])->name('voucher.validate');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    
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

        /*
    |--------------------------------------------------------------------------
    | Profile Extra Pages (Notification & Bookings)
    |--------------------------------------------------------------------------
    */

    // 🔔 Notifications (Customer)
    Route::get('/profile/notifications', function () {
        return view('profile.notifications');
    })->name('profile.notifications');

    // 🚗 Current Booking (Customer)
    Route::get('/bookings/current', [BookingController::class, 'current'])
        ->name('bookings.current');

    // 🕘 Booking History (Customer)
    Route::get('/bookings/history', [BookingController::class, 'history'])
        ->name('bookings.history');

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
    
     // Bookings - ALL with consistent naming
    Route::get('/bookings', [BookingController::class, 'staffIndex'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingController::class, 'staffShow'])->name('bookings.show');
    Route::patch('/bookings/{id}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'staffCancel'])->name('bookings.cancel');
    Route::post('/bookings/{id}/inspection', [BookingController::class, 'storeInspection'])->name('bookings.inspection.store');
    

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function () {
            try {
                $user = auth()->user();
                
                // Get filter type
                $filter = request('filter', 'all');
                
                // Build query - always paginate, never use get()
                $query = $user->notifications();
                
                if ($filter === 'unread') {
                    $query->whereNull('read_at');
                } elseif ($filter === 'read') {
                    $query->whereNotNull('read_at');
                }
                
                // Always use paginate() to get a paginator
                $notifications = $query->latest()->paginate(20)->withQueryString();
                $unreadCount = $user->unreadNotifications()->count();
                
                return view('staff.notifications.index', [
                    'notifications' => $notifications,
                    'unreadCount' => $unreadCount,
                    'filter' => $filter
                ]);
            } catch (\Exception $e) {
                \Log::error('Notifications error: ' . $e->getMessage());
                
                // Return an empty paginator instead of a collection
                $emptyPaginator = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath();
                $notifications = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
                
                return view('staff.notifications.index', [
                    'notifications' => $notifications,
                    'unreadCount' => 0,
                    'filter' => 'all',
                    'error' => 'Notifications system is not yet set up. Please run: php artisan notifications:table && php artisan migrate'
                ]);
            }
        })->name('index');
    
    Route::post('/{id}/mark-as-read', function ($id) {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            
            return redirect()->back()->with('success', 'Notification marked as read');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark notification as read');
        }
    })->name('mark-as-read');
    
    Route::post('/mark-all-as-read', function () {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            
            return redirect()->back()->with('success', 'All notifications marked as read');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark notifications as read');
        }
    })->name('mark-all-as-read');
    
    Route::delete('/{id}', function ($id) {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->delete();
            
            return redirect()->back()->with('success', 'Notification deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete notification');
        }
    })->name('delete');

    Route::get('/test-notification', function () {
    // Send a test notification to the current user
    auth()->user()->notify(new TestNotification());
    
    return redirect()->route('staff.notifications.index')
        ->with('success', 'Test notification sent!');
})->name('test.notification')->middleware(['auth', 'staff.admin']);
});
       // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        // View profile
        Route::get('/profile', function () {
            $user = auth()->user();
            return view('staff.settings.profile', ['user' => $user]);
        })->name('profile');
        
        // Update profile
        Route::put('/profile', function () {
            $user = auth()->user();
            
            $validated = request()->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
            ]);
            
            $user->update($validated);
            
            return redirect()->route('staff.settings.profile')
                ->with('success', 'Profile updated successfully!');
        })->name('updateProfile');
        
        // Update password
        Route::put('/password', function () {
            $user = auth()->user();
            
            $validated = request()->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            // Verify current password
            if (!\Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            
            // Update password
            $user->update([
                'password' => \Hash::make($validated['password'])
            ]);
            
            return redirect()->route('staff.settings.profile')
                ->with('success', 'Password updated successfully!');
        })->name('updatePassword');
        
        // Add this for dashboard data endpoints
        Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    }); // <-- This closes the settings group
    
    // REPORTS ROUTE - Add it HERE, outside settings but inside staff
    Route::get('/reports', function () {
        return view('staff.reports.index');
    })->name('reports.index');
    
}); // This closes the main staff group

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