<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

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
            $query->where('availabiltiy_status', $request->status);
        }

        $vehicles = $query->paginate(6);

        return view('vehicles.index', compact('vehicles'));
    }

    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }
 
}