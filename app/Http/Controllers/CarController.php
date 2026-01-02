<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarController extends Controller
{
    /**
     * Display listing of cars
     */
    public function staffIndex(Request $request): View
    {
        // Start with only available cars
        $query = Car::available();

        // Filter by brand if selected
        if ($request->filled('brand')) {
            $query->byBrand($request->brand);
        }

        // Filter by category/type if selected
        if ($request->filled('carType')) {
            $query->where('carType', $request->carType); // Standard where check
        }

        $cars = $query->get();

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

    public function create(): View
    {
        return view('staff.cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plateNo' => 'required|string|max:20',
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'carType' => 'required|string',
            'transmission' => 'required|string',
            'daily_rate' => 'required|numeric',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload (kalau ada)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()
            ->route('staff.cars.index')
            ->with('success', 'Car added successfully');
    }

    public function edit($id): View
    {
        $car = Car::findOrFail($id);
        return view('staff.cars.edit', compact('car'));
    }

    // Update car
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        $car->update($request->all());

        return redirect()->route('staff.cars.index')
                         ->with('success', 'Car updated successfully!');
    }

    // Delete car
    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return redirect()->route('staff.cars.index')
                         ->with('success', 'Car deleted successfully!');
    }


   
    public function index(): View
    {
        $cars = Car::where('is_available', 1)->get(); // cuma cars available
        return view('cars.index', compact('cars'));
    }



}