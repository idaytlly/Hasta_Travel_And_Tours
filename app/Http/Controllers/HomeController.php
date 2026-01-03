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

    // Dashboard page - redirect based on user type
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login'); // redirect guests to login
        }

        // Redirect based on user type
        if ($user->usertype === 'admin') {
            return redirect()->route('admin.dashboard'); // or wherever admin goes
        }

        if ($user->usertype === 'staff') {
            return redirect()->route('staff.cars'); // Staff goes to car management
        }

        // Customer dashboard
        $user = auth()->user()->refresh();
        $bookings = $user->bookings()->with('car')->get();
        return view('profile.setting', compact('bookings', 'user'));
    }
}