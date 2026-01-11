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

        if ($request->pickup_date && $request->return_date) {
            $pickup_date = $request->pickup_date;
            $return_date = $request->return_date;

            $query->whereDate('pickup_date', '<=', $pickup_date)
                ->whereDate('return_date', '>=', $return_date);
        }

        // Filter by type (car / bike)
        if ($request->type && $request->type != 'all') {
            $query->where('vehicle_type', $request->type); 
        }

        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('availability_status', $request->status);
        }

        $query->whereJsonContains('pickup_location', $request->location);

        if ($request->return_time) {
            $query->where('return_time', $request->return_time);
        }

        if ($request->pickup_time) {
            $query->where('pickup_time', $request->pickup_time);
        }

        Paginator::useBootstrapFive();

        $vehicles = $query->paginate(6)->withQueryString();
        return view('vehicles.index', compact('vehicles'));
    }

    public function show(Vehicle $vehicle)
    {
        // Also load some other vehicles to show as suggestions
        $otherVehicles = Vehicle::where('plate_no', '!=', $vehicle->plate_no)->limit(6)->get();

        return view('vehicles.show', compact('vehicle', 'otherVehicles'));
    }
 
}