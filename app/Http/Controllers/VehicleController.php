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

        // Filter by type (car / bike)
        if ($request->type && $request->type != 'all') {
            $query->where('vehicle_type', $request->type); 
        }

        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('availability_status', $request->status);
        }
        Paginator::useBootstrapFive();

        $vehicles = $query->paginate(6)->withQueryString();
        return view('vehicles.index', compact('vehicles'));
    }

    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }
 
}