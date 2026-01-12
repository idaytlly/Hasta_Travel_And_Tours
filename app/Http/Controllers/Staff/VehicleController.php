<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index()
    {
        $query = Vehicle::query();
        
        // Apply filters
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('plate_no', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('color', 'like', "%{$search}%");
            });
        }
        
        if (request()->has('vehicle_type') && request('vehicle_type')) {
            $query->where('vehicle_type', request('vehicle_type'));
        }
        
        if (request()->has('status') && request('status')) {
            $query->where('availability_status', request('status'));
        }
        
        $vehicles = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('staff.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        return view('staff.vehicles.create');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'vehicle_type' => 'required|in:car,motorcycle',
            'plate_no' => 'required|string|unique:vehicle',
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'distance_travelled' => 'required|numeric|min:0',
            'roadtax_expiry' => 'required|date|after_or_equal:today',
            'transmission' => 'required|in:automatic,manual',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'seating_capacity' => 'required|integer|min:1|max:50',
            'price_perHour' => 'required|numeric|min:0|max:9999',
            'description' => 'nullable|string',
            'maintenance_notes' => 'nullable|string',
            'availability_status' => 'required|in:available,booked,maintenance',
            
            // Display Image
            'display_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            
            // Detail Images (6 images)
            'front_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'right_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'back_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'left_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'interior_front_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'interior_back_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle display image upload
        if ($request->hasFile('display_image')) {
            $displayImagePath = $request->file('display_image')->store('vehicles/display', 'public');
            $validated['display_image'] = $displayImagePath;
        }

        // Handle the 6 detail images
        $detailImages = [];
        $imageFields = [
            'front_image' => 'front',
            'right_image' => 'right',
            'back_image' => 'back',
            'left_image' => 'left',
            'interior_front_image' => 'interior_front',
            'interior_back_image' => 'interior_back',
        ];

        foreach ($imageFields as $field => $key) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('vehicles/detail', 'public');
                $detailImages[$key] = $path;
            }
        }

        // Store detail images as JSON
        if (!empty($detailImages)) {
            $validated['images'] = json_encode($detailImages);
        }

        // FIXED: Get staff_id properly from authenticated user
        $staff = Auth::guard('staff')->user();
        
        if (!$staff) {
            return redirect()->route('staff.login')
                ->with('error', 'You must be logged in as staff to add a vehicle.');
        }
        
        // Use staff_id property, not Auth::id()
        $validated['staff_id'] = $staff->staff_id;

        // Create the vehicle
        Vehicle::create($validated);

        return redirect()->route('staff.vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    /**
     * Display the specified vehicle.
     */
    public function show($plate_no)
    {
        $vehicle = Vehicle::where('plate_no', $plate_no)->firstOrFail();
        return view('staff.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit($plate_no)
    {
        $vehicle = Vehicle::where('plate_no', $plate_no)->firstOrFail();
        return view('staff.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, $plate_no)
    {
        $vehicle = Vehicle::where('plate_no', $plate_no)->firstOrFail();

        // Validation
        $validated = $request->validate([
            'vehicle_type' => 'required|in:car,motorcycle',
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:100',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'distance_travelled' => 'required|numeric|min:0',
            'roadtax_expiry' => 'required|date',
            'transmission' => 'required|in:automatic,manual',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'seating_capacity' => 'required|integer|min:1|max:50',
            'price_perHour' => 'required|numeric|min:0|max:9999',
            'description' => 'nullable|string',
            'maintenance_notes' => 'nullable|string',
            'availability_status' => 'required|in:available,booked,maintenance',
            
            // Display Image (optional for update)
            'display_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            
            // Detail Images (optional for update)
            'front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'right_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'left_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'interior_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'interior_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle display image update
        if ($request->hasFile('display_image')) {
            // Delete old display image if exists
            if ($vehicle->display_image && Storage::disk('public')->exists($vehicle->display_image)) {
                Storage::disk('public')->delete($vehicle->display_image);
            }
            
            $displayImagePath = $request->file('display_image')->store('vehicles/display', 'public');
            $validated['display_image'] = $displayImagePath;
        } else {
            // Keep existing display image
            $validated['display_image'] = $vehicle->display_image;
        }

        // Handle detail images update
        $currentImages = $vehicle->images ? json_decode($vehicle->images, true) : [];
        
        $imageFields = [
            'front_image' => 'front',
            'right_image' => 'right',
            'back_image' => 'back',
            'left_image' => 'left',
            'interior_front_image' => 'interior_front',
            'interior_back_image' => 'interior_back',
        ];

        foreach ($imageFields as $field => $key) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                if (isset($currentImages[$key]) && Storage::disk('public')->exists($currentImages[$key])) {
                    Storage::disk('public')->delete($currentImages[$key]);
                }
                
                // Upload new image
                $path = $request->file($field)->store('vehicles/detail', 'public');
                $currentImages[$key] = $path;
            } elseif (isset($currentImages[$key])) {
                // Keep existing image
                $currentImages[$key] = $currentImages[$key];
            }
        }

        // Store updated images as JSON
        if (!empty($currentImages)) {
            $validated['images'] = json_encode($currentImages);
        } else {
            $validated['images'] = null;
        }

        // Update the vehicle
        $vehicle->update($validated);

        return redirect()->route('staff.vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy($plate_no)
    {
        $vehicle = Vehicle::where('plate_no', $plate_no)->firstOrFail();
        
        // Delete display image if exists
        if ($vehicle->display_image && Storage::disk('public')->exists($vehicle->display_image)) {
            Storage::disk('public')->delete($vehicle->display_image);
        }
        
        // Delete detail images if exist
        if ($vehicle->images) {
            $detailImages = json_decode($vehicle->images, true);
            foreach ($detailImages as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }
        
        // Delete the vehicle
        $vehicle->delete();

        return redirect()->route('staff.vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    /**
     * Update vehicle status quickly (for AJAX requests if needed).
     */
    public function updateStatus(Request $request, $plate_no)
    {
        $request->validate([
            'status' => 'required|in:available,booked,maintenance'
        ]);

        $vehicle = Vehicle::where('plate_no', $plate_no)->firstOrFail();
        $vehicle->update(['availability_status' => $request->status]);

        return response()->json(['success' => true]);
    }
}