<?php
// app/Http/Controllers/Staff/VehicleController.php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Vehicle::count(),
            'available' => Vehicle::where('availability_status', 'available')->count(),
            'booked' => Vehicle::where('availability_status', 'booked')->count(),
            'maintenance' => Vehicle::where('availability_status', 'maintenance')->count(),
        ];
        
        return view('staff.vehicles.index', compact('vehicles', 'stats'));
    }

    public function create()
    {
        $vehicleTypes = Vehicle::distinct()->pluck('vehicle_type')->sort();
        $transmissionTypes = ['Manual', 'Automatic'];
        $fuelTypes = ['Petrol', 'Diesel', 'Electric', 'Hybrid'];
        
        return view('staff.vehicles.create', compact('vehicleTypes', 'transmissionTypes', 'fuelTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_no' => 'required|unique:vehicles|max:20',
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:50',
            'transmission' => 'required|in:Manual,Automatic',
            'fuel_type' => 'required|in:Petrol,Diesel,Electric,Hybrid',
            'seating_capacity' => 'required|integer|min:1|max:100',
            'price_perHour' => 'required|numeric|min:0',
            'price_perDay' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|array',
            'availability_status' => 'required|in:available,booked,maintenance',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'maintenance_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Handle images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    $imagePaths[] = $path;
                }
            }

            // Create vehicle
            $vehicle = Vehicle::create([
                'plate_no' => $request->plate_no,
                'name' => $request->name,
                'model' => $request->model,
                'vehicle_type' => $request->vehicle_type,
                'transmission' => $request->transmission,
                'fuel_type' => $request->fuel_type,
                'seating_capacity' => $request->seating_capacity,
                'price_perHour' => $request->price_perHour,
                'price_perDay' => $request->price_perDay,
                'description' => $request->description,
                'features' => $request->features ? json_encode($request->features) : null,
                'availability_status' => $request->availability_status,
                'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
                'maintenance_notes' => $request->maintenance_notes,
            ]);

            DB::commit();
            
            return redirect()->route('staff.vehicles.index')
                           ->with('success', 'Vehicle added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add vehicle: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('bookings.customer'); // Load relationships if needed
        return view('staff.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $vehicleTypes = Vehicle::distinct()->pluck('vehicle_type')->sort();
        $transmissionTypes = ['Manual', 'Automatic'];
        $fuelTypes = ['Petrol', 'Diesel', 'Electric', 'Hybrid'];
        
        return view('staff.vehicles.edit', compact('vehicle', 'vehicleTypes', 'transmissionTypes', 'fuelTypes'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate_no' => 'required|max:20|unique:vehicles,plate_no,' . $vehicle->plate_no . ',plate_no',
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:50',
            'transmission' => 'required|in:Manual,Automatic',
            'fuel_type' => 'required|in:Petrol,Diesel,Electric,Hybrid',
            'seating_capacity' => 'required|integer|min:1|max:100',
            'price_perHour' => 'required|numeric|min:0',
            'price_perDay' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'features' => 'nullable|array',
            'availability_status' => 'required|in:available,booked,maintenance',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'maintenance_notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Handle images
            $imagePaths = json_decode($vehicle->images ?? '[]', true) ?: [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('vehicles', 'public');
                    $imagePaths[] = $path;
                }
            }
            
            // Handle image removal
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imagePath) {
                    if (($key = array_search($imagePath, $imagePaths)) !== false) {
                        Storage::disk('public')->delete($imagePath);
                        unset($imagePaths[$key]);
                    }
                }
                $imagePaths = array_values($imagePaths); // Reindex array
            }

            $vehicle->update([
                'plate_no' => $request->plate_no,
                'name' => $request->name,
                'model' => $request->model,
                'vehicle_type' => $request->vehicle_type,
                'transmission' => $request->transmission,
                'fuel_type' => $request->fuel_type,
                'seating_capacity' => $request->seating_capacity,
                'price_perHour' => $request->price_perHour,
                'price_perDay' => $request->price_perDay,
                'description' => $request->description,
                'features' => $request->features ? json_encode($request->features) : null,
                'availability_status' => $request->availability_status,
                'images' => !empty($imagePaths) ? json_encode($imagePaths) : null,
                'maintenance_notes' => $request->maintenance_notes,
            ]);

            DB::commit();
            
            return redirect()->route('staff.vehicles.index')
                           ->with('success', 'Vehicle updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update vehicle: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy(Vehicle $vehicle)
    {
        DB::beginTransaction();
        try {
            // Delete images
            if ($vehicle->images) {
                $images = json_decode($vehicle->images, true);
                foreach ($images as $imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            
            $vehicle->delete();
            
            DB::commit();
            
            return redirect()->route('staff.vehicles.index')
                           ->with('success', 'Vehicle deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }

    public function toggleAvailability(Vehicle $vehicle)
    {
        $newStatus = $vehicle->availability_status === 'available' ? 'maintenance' : 'available';
        $vehicle->update(['availability_status' => $newStatus]);
        
        return back()->with('success', "Vehicle status changed to {$newStatus}!");
    }

    public function maintenance(Vehicle $vehicle)
    {
        return view('staff.vehicles.maintenance', compact('vehicle'));
    }

    public function updateMaintenance(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'maintenance_notes' => 'required|string|max:500',
            'availability_status' => 'required|in:available,maintenance',
        ]);
        
        $vehicle->update([
            'maintenance_notes' => $request->maintenance_notes,
            'availability_status' => $request->availability_status,
        ]);
        
        return redirect()->route('staff.vehicles.index')
                       ->with('success', 'Maintenance details updated successfully!');
    }
}