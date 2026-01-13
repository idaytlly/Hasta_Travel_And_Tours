<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Bootstrap application for facades
$kernel->bootstrap();

use App\Models\Staff;

$staff = Staff::where('role', 'admin')->first() ?: Staff::first();
if (!$staff) {
    echo "NO_STAFF_FOUND\n";
    exit(1);
}

$urls = [
    '/api/staff/bookings',
    '/api/staff/delivery/tasks'
];

// Provide a dummy request in the container so auth guards initialize correctly
$dummyRequest = Illuminate\Http\Request::create('/', 'GET');
$app->instance('request', $dummyRequest);
$app->instance(Illuminate\Http\Request::class, $dummyRequest);

// Autoload mismatch: StaffBookingsController class file is named StaffBookingController.php (singular)
// Require it manually so PHP knows about the class before resolving it
require_once __DIR__ . '/../app/Http/Controllers/Api/Staff/StaffBookingController.php';

// Ensure DB facade alias exists in the global namespace (some controllers refer to DB unqualified)
if (!class_exists('DB')) {
    class_alias(Illuminate\Support\Facades\DB::class, 'DB');
}

// Instead of routing through the kernel (which enforces auth middleware), we'll call controllers directly
$bookingsController = $app->make(App\Http\Controllers\Api\Staff\StaffBookingsController::class);
$deliveryController = $app->make(App\Http\Controllers\Api\Staff\StaffDeliveryController::class);

// 1) Bookings
$request = Illuminate\Http\Request::create('/api/staff/bookings', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
$request->setUserResolver(function () use ($staff) { return $staff; });
$response = $bookingsController->getBookings($request);
echo "=== /api/staff/bookings ===\n";
echo $response->getContent() . "\n\n";

// 2) Delivery tasks
$request = Illuminate\Http\Request::create('/api/staff/delivery/tasks', 'GET', [], [], [], ['HTTP_ACCEPT' => 'application/json']);
$request->setUserResolver(function () use ($staff) { return $staff; });
$response = $deliveryController->getTasks($request);
echo "=== /api/staff/delivery/tasks ===\n";
echo $response->getContent() . "\n\n";
