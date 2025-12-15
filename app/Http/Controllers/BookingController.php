<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Car $car)
    {
        return view('booking.create', compact('car'));
    }

    public function store(Request $request, Car $car)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = $request->file('document')->store('documents', 'public');

        Booking::create([
            'user_id' => auth()->id(),
            'car_id' => $car->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'document_path' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking submitted successfully!');
    }
}
