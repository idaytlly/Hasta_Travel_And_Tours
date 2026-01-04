<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display listing of cars for customers
     */
    public function index(Request $request): View
    {
        $query = Car::query();

        // Filter by brand if selected
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        // Filter by category/type if selected
        if ($request->filled('carType')) {
            $query->where('carType', $request->carType);
        }

        $cars = $query->get();

        return view('cars.index', compact('cars'));
    }

    /**
     * Display listing of cars for staff
     */
    public function staffIndex(Request $request): View
    {
        $query = Car::query();

        // Filter by brand if selected
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        // Filter by type if selected (matches the 'type' parameter from your routes)
        if ($request->filled('type')) {
            $query->where('carType', $request->type);
        }

        $cars = $query->latest()->get();

        return view('staff.cars.index', compact('cars'));
    }

    /**
     * Show car details
     */
    public function show($id): View
    {
        $car = Car::findOrFail($id);
        
        // Get 3 other cars of the same brand, excluding current one
        $otherCars = Car::where('brand', $car->brand)
                        ->where('id', '!=', $id)
                        ->limit(3)
                        ->get();

        return view('cars.show', compact('car', 'otherCars'));
    }

    /**
     * Show the form for creating a new car
     */
    public function create(): View
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized');
        }
        
        return view('staff.cars.create');
    }

    /**
     * Store a newly created car
     */
    public function store(Request $request)
{
    if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
        abort(403, 'Unauthorized');
    }

    // Determine if this is a motorcycle based on vehicle_type or category
    $isMotorcycle = $request->vehicle_type === 'motorcycle' || $request->category === 'motorcycle';

    $validated = $request->validate([
        'license_plate' => 'required|string|max:20|unique:cars,license_plate',
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        'category' => 'nullable|string|max:255',
        'transmission' => 'required|string|in:manual,automatic,Manual,Automatic',
        'fuel_type' => 'nullable|string|max:50',
        'daily_rate' => 'required|numeric|min:0',
        'is_available' => 'required|boolean',
        'air_conditioner' => 'nullable|boolean',
        'passengers' => 'nullable|integer|min:1|max:15',
        'seats' => 'nullable|integer|min:1|max:15',
        'engine_capacity' => 'nullable|integer|min:50|max:2000',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'vehicle_type' => 'nullable|string|in:car,motorcycle',
    ]);

    // Set category to motorcycle if vehicle_type is motorcycle
    if ($isMotorcycle) {
        $validated['category'] = 'motorcycle';
    }

    // Generate car name
    $validated['name'] = $validated['brand'] . ' ' . $validated['model'] . ' ' . $validated['year'];
    
    // Normalize transmission to proper case
    $validated['transmission'] = ucfirst(strtolower($validated['transmission']));
    
    // Set default values based on vehicle type
    if ($isMotorcycle) {
        $validated['air_conditioner'] = 0; // Motorcycles don't have AC
        $validated['passengers'] = $validated['passengers'] ?? 2;
        $validated['seats'] = $validated['seats'] ?? 2;
    } else {
        $validated['air_conditioner'] = $request->has('air_conditioner') ? 1 : ($validated['air_conditioner'] ?? 1);
        $validated['passengers'] = $validated['passengers'] ?? 5;
        $validated['seats'] = $validated['seats'] ?? 5;
    }
    
    $validated['status'] = $validated['is_available'] ? 'available' : 'unavailable';

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('cars', 'public');
        $validated['image'] = $imagePath;
    }

    Car::create($validated);

    return redirect()->route('staff.cars.index')->with('success', 'Vehicle added successfully!');
    }

    /**
     * Show the form for editing the car
     */
    public function edit(Car $car): View
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized');
        }
        
        return view('staff.cars.edit', compact('car'));
    }

    /**
     * Update the specified car
     */
    public function update(Request $request, Car $car)
{
    if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
        abort(403, 'Unauthorized');
    }

    $validated = $request->validate([
        'license_plate' => 'required|string|max:20|unique:cars,license_plate,' . $car->id,
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
        'category' => 'nullable|string|in:Sedan,Hatchback,MPV,SUV,Minivan',
        'transmission' => 'required|string|in:manual,automatic,Manual,Automatic',
        'fuel_type' => 'nullable|string|max:50',
        'daily_rate' => 'required|numeric|min:0',
        'is_available' => 'required|boolean',
        'air_conditioner' => 'nullable|boolean',
        'passengers' => 'nullable|integer|min:1|max:15',
        'seats' => 'nullable|integer|min:1|max:15',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    // Generate car name
    $validated['name'] = $validated['brand'] . ' ' . $validated['model'] . ' ' . $validated['year'];
    
    // Normalize transmission
    $validated['transmission'] = ucfirst(strtolower($validated['transmission']));
    
    // Set default values if not provided
    $validated['air_conditioner'] = $request->has('air_conditioner') ? 1 : ($validated['air_conditioner'] ?? 1);
    $validated['passengers'] = $validated['passengers'] ?? 5;
    $validated['seats'] = $validated['seats'] ?? 5;
    $validated['status'] = $validated['is_available'] ? 'available' : 'unavailable';

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($car->image && file_exists(storage_path('app/public/' . $car->image))) {
            unlink(storage_path('app/public/' . $car->image));
        }
        
        $imagePath = $request->file('image')->store('cars', 'public');
        $validated['image'] = $imagePath;
    }

    $car->update($validated);

    return redirect()->route('staff.cars.index')->with('success', 'Vehicle updated successfully!');
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

            return redirect()->route('staff.cars.index')  // Changed from staff.cars to staff.cars.index
                ->with('success', "{$carName} deleted successfully!");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete car: ' . $e->getMessage());
        }
    }

}