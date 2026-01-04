<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    /**
     * Display all cars
     */
    public function index(Request $request)
    {
        $query = Car::query();
        
        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%')
                  ->orWhere('license_plate', 'like', '%' . $request->search . '%');
            });
        }
        
        $cars = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('staff.cars.index', compact('cars'));
    }

    /**
     * Show single car details
     */
    public function show(Car $car)
    {
        $car->load(['bookings' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);
        
        return view('staff.cars.show', compact('car'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('staff.cars.create');
    }

    /**
     * Store new car
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'registration_number' => 'nullable|string|max:20',
            'license_plate' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'transmission' => 'required|string',
            'fuel_type' => 'nullable|string',
            'seats' => 'nullable|integer|min:1|max:50',
            'passengers' => 'nullable|integer|min:1|max:50',
            'air_conditioner' => 'nullable|boolean',
            'engine_capacity' => 'nullable|integer|min:50|max:2000',
            'daily_rate' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
            'vehicle_type' => 'nullable|string|in:car,motorcycle',
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('cars', 'public');
            }

            // Set defaults
            $validated['status'] = $validated['is_available'] ?? true ? 'available' : 'maintenance';
            $validated['is_available'] = $validated['is_available'] ?? true;
            
            // Auto-detect vehicle type if not set
            if (!isset($validated['vehicle_type'])) {
                // If has engine_capacity or category is motorcycle-related, it's a motorcycle
                if (isset($validated['engine_capacity']) || 
                    in_array(strtolower($validated['category'] ?? ''), ['sport', 'cruiser', 'touring', 'standard', 'scooter', 'adventure'])) {
                    $validated['vehicle_type'] = 'motorcycle';
                } else {
                    $validated['vehicle_type'] = 'car';
                }
            }

            $car = Car::create($validated);

            // 🔔 NOTIFICATION: Notify all staff about new vehicle
            $plateNumber = $car->registration_number ?? $car->license_plate ?? 'N/A';
            NotificationHelper::notifyAllStaff(
                'system',
                'New Vehicle Added',
                "{$car->name} ({$plateNumber}) has been added to the fleet by " . auth()->user()->name,
                route('staff.cars.show', $car->id),
                ['car_id' => $car->id, 'car_name' => $car->name]
            );

            DB::commit();

            return redirect()->route('staff.cars.index')
                ->with('success', 'Vehicle added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add vehicle: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit(Car $car)
    {
        // Check if it's a motorcycle and return appropriate view
        if ($car->vehicle_type === 'motorcycle') {
            return view('staff.motorcycles.edit', compact('car'));
        }
        
        return view('staff.cars.edit', compact('car'));
    }

    /**
     * Update car
     */
    public function update(Request $request, Car $car)
    {
        // Base validation rules
        $rules = [
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'category' => 'nullable|string|max:50',
            'transmission' => 'required|string',
            'fuel_type' => 'nullable|string',
            'daily_rate' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_available' => 'required|boolean',
        ];

        // Add vehicle-specific validation
        if ($car->vehicle_type === 'motorcycle') {
            $rules['license_plate'] = 'required|string|max:20|unique:cars,license_plate,' . $car->id;
            $rules['engine_capacity'] = 'nullable|integer|min:50|max:2000';
        } else {
            $rules['license_plate'] = 'required|string|max:20|unique:cars,license_plate,' . $car->id;
            $rules['seats'] = 'nullable|integer|min:1|max:15';
            $rules['passengers'] = 'nullable|integer|min:1|max:15';
            $rules['air_conditioner'] = 'nullable|boolean';
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($car->image) {
                    Storage::disk('public')->delete($car->image);
                }
                $validated['image'] = $request->file('image')->store('cars', 'public');
            }

            $car->update($validated);

            DB::commit();

            return redirect()->route('staff.cars.index')
                ->with('success', ($car->vehicle_type === 'motorcycle' ? 'Motorcycle' : 'Car') . ' updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update vehicle: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Car $car)
    {
        try {
            // Check if car has active bookings
            if ($car->bookings()->whereIn('status', ['pending', 'confirmed', 'active'])->exists()) {
                return back()->with('error', 'Cannot delete vehicle with active bookings!');
            }

            // Delete image
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }

            $vehicleType = $car->vehicle_type === 'motorcycle' ? 'Motorcycle' : 'Car';
            $vehicleName = $car->name;
            
            $car->delete();

            // Force a fresh redirect with cache headers
            return redirect()
                ->route('staff.cars.index')
                ->with('success', "{$vehicleType} {$vehicleName} deleted successfully!")
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }

    /**
     * Update car availability status
     */
    public function updateAvailability(Request $request, Car $car)
    {
        $request->validate([
            'is_available' => 'required|boolean',
            'reason' => 'nullable|string|max:500'
        ]);

        $oldStatus = $car->is_available;
        $newStatus = $request->is_available;

        $car->update([
            'is_available' => $newStatus,
            'status' => $newStatus ? 'available' : 'maintenance'
        ]);

        // 🔔 NOTIFICATION: Notify staff about availability change
        if ($oldStatus != $newStatus) {
            $message = $newStatus 
                ? "{$car->name} is now available for bookings"
                : "{$car->name} is now unavailable" . ($request->reason ? " - {$request->reason}" : "");

            NotificationHelper::notifyAllStaff(
                'maintenance',
                'Car Availability Updated',
                $message,
                route('staff.cars.show', $car->id),
                [
                    'car_id' => $car->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'reason' => $request->reason
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Availability updated successfully'
        ]);
    }

    /**
     * Add maintenance record
     */
    public function addMaintenance(Request $request, Car $car)
    {
        $request->validate([
            'maintenance_type' => 'required|string|max:255',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'maintenance_date' => 'required|date',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date'
        ]);

        // Get existing maintenance records or initialize empty array
        $maintenanceRecords = $car->maintenance_records ?? [];
        
        // Add new record
        $maintenanceRecords[] = [
            'type' => $request->maintenance_type,
            'description' => $request->description,
            'cost' => $request->cost,
            'date' => $request->maintenance_date,
            'next_date' => $request->next_maintenance_date,
            'performed_by' => auth()->user()->name,
            'created_at' => now()->toDateTimeString()
        ];

        $car->update([
            'maintenance_records' => $maintenanceRecords,
            'last_maintenance_date' => $request->maintenance_date
        ]);

        // Set car as unavailable during maintenance if specified
        if ($request->set_unavailable) {
            $car->update([
                'is_available' => false,
                'status' => 'maintenance'
            ]);
        }

        // 🔔 NOTIFICATION: Notify staff about maintenance
        NotificationHelper::notifyAllStaff(
            'maintenance',
            'Maintenance Scheduled',
            "{$car->name} - {$request->maintenance_type} scheduled for {$request->maintenance_date}",
            route('staff.cars.show', $car->id),
            [
                'car_id' => $car->id,
                'maintenance_type' => $request->maintenance_type,
                'date' => $request->maintenance_date
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Maintenance record added successfully'
        ]);
    }

    /**
     * Update mileage
     */
    public function updateMileage(Request $request, Car $car)
    {
        $request->validate([
            'mileage' => 'required|integer|min:' . ($car->mileage ?? 0)
        ]);

        $car->update(['mileage' => $request->mileage]);

        return response()->json([
            'success' => true,
            'message' => 'Mileage updated successfully'
        ]);
    }
}