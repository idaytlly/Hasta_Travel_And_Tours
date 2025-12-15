<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    // Public car listing
    public function index()
    {
        $cars = Car::all();
        return view('cars.index', compact('cars'));
    }

    // Staff car management
    public function staffIndex()
    {
        $cars = Car::all();
        return view('staff.cars.index', compact('cars'));
    }

    public function create() { return view('staff.cars.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Car::create($request->all());
        return redirect()->route('staff.cars')->with('success', 'Car added successfully!');
    }

    public function edit($id)
    {
        $car = Car::findOrFail($id);
        return view('staff.cars.edit', compact('car'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $car = Car::findOrFail($id);
        $car->update($request->all());
        return redirect()->route('staff.cars')->with('success', 'Car updated successfully!');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);
        $car->delete();
        return redirect()->route('staff.cars')->with('success', 'Car deleted successfully!');
    }
}
