<?php
// app/Http/Controllers/Staff/VehicleController.php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Maintenance;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::query();
        
        // Filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('availability_status', $request->status);
        }
        
        if ($request->has('type') && $request->type != 'all') {
            $query->where('vehicle_type', $request->type);
        }
        
        if ($request->has('fuel_type') && $request->fuel_type != 'all') {
            $query->where('fuel_type', $request->fuel_type);
        }
        
        if ($request->has('transmission') && $request->transmission != 'all') {
            $query->where('transmission', $request->transmission);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('plate_no', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%");
            });
        }
        
        $vehicles = $query->with(['currentBooking'])->orderBy('name')->paginate(20);
        
        $stats = [
            'total' => Vehicle::count(),
            'available' => Vehicle::available()->count(),
            'booked' => Vehicle::booked()->count(),
            'maintenance' => Vehicle::maintenance()->count(),
            'unavailable' => Vehicle::unavailable()->count(),
        ];
        
        return view('staff.vehicles.index', compact('vehicles', 'stats'));
    }

    public function show($id)
    {
        $vehicle = Vehicle::with(['bookings' => function($query) {
            $query->where('booking_status', '!=', 'cancelled')
                  ->orderBy('pickup_date', 'desc')
                  ->limit(10);
        }, 'maintenances' => function($query) {
            $query->orderBy('maintenance_date', 'desc')
                  ->limit(5);
        }, 'inspections' => function($query) {
            $query->orderBy('inspection_date', 'desc')
                  ->limit(5);
        }])->findOrFail($id);
        
        // Current booking if any
        $currentBooking = $vehicle->currentBooking;
        
        // Upcoming maintenance
        $upcomingMaintenance = $vehicle->maintenances()
            ->where('maintenance_status', 'scheduled')
            ->where('maintenance_date', '>=', now())
            ->orderBy('maintenance_date')
            ->first();
        
        return view('staff.vehicles.show', compact('vehicle', 'currentBooking', 'upcomingMaintenance'));
    }

    public function create()
    {
        return view('staff.vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_no' => 'required|unique:vehicle,plate_no',
            'name' => 'required|string|max:100',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'color' => 'required|string|max:30',
            'vehicle_type' => 'required|string',
            'seating_capacity' => 'required|integer|min:1|max:20',
            'transmission' => 'required|in:automatic,manual',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'mileage' => 'required|integer|min:0',
            'price_perHour' => 'required|numeric|min:0',
            'availability_status' => 'required|in:available,booked,maintenance,unavailable',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string|max:500',
            'insurance_expiry' => 'nullable|date',
            'features' => 'nullable|array'
        ]);

        $vehicle = Vehicle::create($request->all());
        
        return redirect()->route('staff.vehicles.show', $vehicle->plate_no)
                       ->with('success', 'Vehicle added successfully!');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('staff.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:2000|max:' . date('Y'),
            'color' => 'required|string|max:30',
            'vehicle_type' => 'required|string',
            'seating_capacity' => 'required|integer|min:1|max:20',
            'transmission' => 'required|in:automatic,manual',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'mileage' => 'required|integer|min:0',
            'price_perHour' => 'required|numeric|min:0',
            'availability_status' => 'required|in:available,booked,maintenance,unavailable',
            'image_url' => 'nullable|url',
            'description' => 'nullable|string|max:500',
            'insurance_expiry' => 'nullable|date',
            'features' => 'nullable|array'
        ]);

        $vehicle->update($request->all());
        
        return redirect()->route('staff.vehicles.show', $vehicle->plate_no)
                       ->with('success', 'Vehicle updated successfully!');
    }

    public function updateAvailability(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $request->validate([
            'availability_status' => 'required|in:available,booked,maintenance,unavailable',
            'status_notes' => 'nullable|string|max:500'
        ]);

        $vehicle->update([
            'availability_status' => $request->availability_status
        ]);

        // If putting in maintenance, create maintenance record
        if ($request->availability_status === 'maintenance') {
            Maintenance::create([
                'maintenance_id' => 'MNT' . now()->format('Ymd') . strtoupper(uniqid()),
                'plate_no' => $vehicle->plate_no,
                'staff_id' => Auth::guard('staff')->user()->staff_id,
                'maintenance_date' => now(),
                'maintenance_type' => 'routine',
                'maintenance_status' => 'in_progress',
                'description' => $request->status_notes ?? 'Routine maintenance',
                'notes' => 'Vehicle status changed to maintenance'
            ]);
        }

        return back()->with('success', 'Vehicle availability updated successfully!');
    }

    public function scheduleMaintenance(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $request->validate([
            'maintenance_date' => 'required|date|after:today',
            'maintenance_type' => 'required|in:routine,repair,service,inspection',
            'description' => 'required|string|max:500',
            'estimated_cost' => 'nullable|numeric|min:0'
        ]);

        Maintenance::create([
            'maintenance_id' => 'MNT' . now()->format('Ymd') . strtoupper(uniqid()),
            'plate_no' => $vehicle->plate_no,
            'staff_id' => Auth::guard('staff')->user()->staff_id,
            'maintenance_date' => $request->maintenance_date,
            'maintenance_type' => $request->maintenance_type,
            'maintenance_status' => 'scheduled',
            'description' => $request->description,
            'cost' => $request->estimated_cost,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Maintenance scheduled successfully!');
    }

    public function bookingHistory($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $bookings = $vehicle->bookings()
            ->with(['customer', 'payments'])
            ->where('booking_status', '!=', 'cancelled')
            ->orderBy('pickup_date', 'desc')
            ->paginate(20);
        
        return view('staff.vehicles.booking-history', compact('vehicle', 'bookings'));
    }

    public function maintenanceHistory($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $maintenances = $vehicle->maintenances()
            ->orderBy('maintenance_date', 'desc')
            ->paginate(20);
        
        return view('staff.vehicles.maintenance-history', compact('vehicle', 'maintenances'));
    }
}