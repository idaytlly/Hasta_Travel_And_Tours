<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Car;

class AdminController extends Controller
{
    /**
     * Admin Dashboard - Statistics & Overview
     */
    public function index()
    {
        $revenue = Booking::where('status', 'approved')->sum('total_price');
        $todayUsers = User::whereDate('created_at', now()->today())->count();
        $totalSales = Booking::where('status', 'approved')->count();
        $recentLogs = Car::latest()->take(5)->get();

        return view('admin.dashboard', compact('revenue', 'todayUsers', 'totalSales', 'recentLogs'));
    }

    /**
     * Booking Management - List all bookings
     */
    public function bookings()
    {
        $bookings = Booking::with(['user', 'car'])->latest()->get();
        return view('admin.bookings.index', compact('bookings')); 
    }

    /**
     * Booking Management - Show specific booking details
     */
    public function showBooking($id)
    {
        // The "with('user')" part is critical here
        $booking = Booking::with(['user', 'car'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Booking Management - Process Approval/Rejection
     */
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected,pending,confirmed,cancelled'
    ]);

    $booking = Booking::findOrFail($id);
    
    $booking->update([
        'status' => $request->status
    ]);

    // Redirect to the same booking detail page
    return redirect()->route('admin.bookings.show', $id)
        ->with('success', 'Booking status updated to ' . strtoupper($request->status) . ' successfully!');
}
    /**
     * Vehicle Management - List all cars
     */
    public function cars()
    {
        $cars = Car::latest()->get();
        return view('admin.cars.index', compact('cars'));
    }

    /**
     * Vehicle Management - Show Add Car Form
     */
    public function createCar()
    {
        return view('admin.cars.create');
    }

    /**
     * Vehicle Management - Store new car in database
     */
    public function storeCar(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'plate_number' => 'required|string|unique:cars',
            'type' => 'required|string',
            'price_per_day' => 'required|numeric',
            'status' => 'required|in:available,rented,maintenance',
        ]);

        Car::create($validated);

        return redirect()->route('admin.cars.index')->with('success', 'New vehicle added successfully!');
    }

    /**
     * Vehicle Management - Delete a car
     */
    public function destroyCar($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return redirect()->route('admin.cars.index')->with('success', 'Vehicle removed from fleet.');
    }
}