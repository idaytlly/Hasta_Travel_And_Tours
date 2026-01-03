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
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%');
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
            'registration_number' => 'required|string|unique:cars,registration_number|max:20',
            'color' => 'nullable|string|max:50',
            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
            'seats' => 'required|integer|min:2|max:50',
            'daily_rate' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
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

            $car = Car::create($validated);

            // 🔔 NOTIFICATION: Notify all staff about new car
            NotificationHelper::notifyAllStaff(
                'system',
                'New Car Added',
                "{$car->name} ({$car->registration_number}) has been added to the fleet by " . auth()->user()->name,
                route('staff.cars.show', $car->id),
                ['car_id' => $car->id, 'car_name' => $car->name]
            );

            DB::commit();

            return redirect()->route('staff.cars.index')
                ->with('success', 'Car added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add car: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit(Car $car)
    {
        return view('staff.cars.edit', compact('car'));
    }

    /**
     * Update car
     */
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'registration_number' => 'required|string|max:20|unique:cars,registration_number,' . $car->id,
            'color' => 'nullable|string|max:50',
            'transmission' => 'required|in:manual,automatic',
            'fuel_type' => 'required|in:petrol,diesel,hybrid,electric',
            'seats' => 'required|integer|min:2|max:50',
            'daily_rate' => 'required|numeric|min:0',
            'mileage' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
        ]);

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
                ->with('success', 'Car updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update car: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete car
     */
    public function destroy(Car $car)
    {
        try {
            // Check if car has active bookings
            if ($car->bookings()->whereIn('status', ['pending', 'confirmed', 'active'])->exists()) {
                return back()->with('error', 'Cannot delete car with active bookings!');
            }

            // Delete image
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }

            $carName = $car->name;
            $car->delete();

            return redirect()->route('staff.cars.index')
                ->with('success', "{$carName} deleted successfully!");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete car: ' . $e->getMessage());
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