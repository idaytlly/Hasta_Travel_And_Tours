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

        $validated = $request->validate([
            'plateNo' => 'required|string|max:20|unique:cars,plateNo',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'carType' => 'required|string|in:Sedan,Hatchback,MPV,SUV',
            'transmission' => 'required|string|in:manual,automatic',
            'fuel_type' => 'nullable|string|max:50',
            'daily_rate' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'air_conditioner' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('cars', 'public');
        }

        // Set default for air_conditioner if not present
        $validated['air_conditioner'] = $request->has('air_conditioner') ? true : false;

        Car::create($validated);

        return redirect()
            ->route('staff.cars')
            ->with('success', 'Vehicle added successfully!');
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
            'plateNo' => 'required|string|max:20|unique:cars,plateNo,' . $car->id,
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'carType' => 'required|string|in:Sedan,Hatchback,MPV,SUV',
            'transmission' => 'required|string|in:manual,automatic',
            'fuel_type' => 'nullable|string|max:50',
            'daily_rate' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'air_conditioner' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $validated['image'] = $request->file('image')->store('cars', 'public');
        }

        // Set default for air_conditioner if not present
        $validated['air_conditioner'] = $request->has('air_conditioner') ? true : false;

        $car->update($validated);

        return redirect()
            ->route('staff.cars')
            ->with('success', 'Vehicle updated successfully!');
    }

    /**
     * Remove the specified car
     */
    public function destroy(Car $car)
    {
        if (!in_array(auth()->user()->usertype, ['staff', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        // Delete image if exists
        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();

        return redirect()
            ->route('staff.cars')
            ->with('success', 'Vehicle deleted successfully!');
    }


   
    public function index(): View
    {
        $cars = Car::where('is_available', 1)->get(); // cuma cars available
        return view('cars.index', compact('cars'));
    }



}