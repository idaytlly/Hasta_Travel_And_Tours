<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();

        // Filter by date range (only if search is done)
        if ($request->pickup_date && $request->return_date) {
            $query->whereDate('pickup_date', '<=', $request->pickup_date)
                ->whereDate('return_date', '>=', $request->return_date);
        }

        // Filter by vehicle type
        if ($request->type && $request->type !== 'all') {
            $query->where('vehicle_type', $request->type);
        }

        // Filter by availability status
        if ($request->status && $request->status !== 'all') {
            $query->where('availability_status', $request->status);
        }

        // ✅ Filter by pickup location ONLY if selected
        if ($request->filled('location')) {
            $query->whereJsonContains('pickup_location', $request->location);
        }

        // Filter by pickup time
        if ($request->filled('pickup_time')) {
            $query->where('pickup_time', $request->pickup_time);
        }

        // Filter by return time
        if ($request->filled('return_time')) {
            $query->where('return_time', $request->return_time);
        }

        Paginator::useBootstrapFive();

        $vehicles = $query->paginate(6)->withQueryString();

        return view('vehicles.index', compact('vehicles'));
    }

    public function show(Vehicle $vehicle)
    {
        // Also load some other vehicles to show as suggestions
        $otherVehicles = Vehicle::where('plate_no', '!=', $vehicle->plate_no)->limit(6)->get();
        $images = json_decode($vehicle->images, true);

        return view('vehicles.show', compact('vehicle', 'images'));

        return view('vehicles.show', compact('vehicle', 'otherVehicles'));
    }
 
}