<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

class HomeController extends Controller
{
    // Welcome page
    public function index()
    {
        // Fetch cars from database
        $cars = Car::all();

        // Return the welcome view exactly as it is, no layout changes
        return view('welcome', compact('cars'));
    }

    // Dashboard page
    public function dashboard()
    {
       $user = auth()->user();

        if (!$user) {
            return redirect()->route('login'); // redirect guests to login
        }

        $user = auth()->user()->refresh(); // reload latest info
        $bookings = $user->bookings()->with('car')->get();
        return view('profile.setting', compact('bookings', 'user'));
    }
}
