<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('staff.dashboard.index');
    }

    public function bookings()
    {
        return view('staff.bookings.index');
    }

    public function delivery()
    {
        return view('staff.delivery.index');
    }

    public function reports()
    {
        return view('staff.reports.index');
    }

    public function vehicles()
    {
        return view('staff.vehicles.index');
    }

    public function customers()
    {
        return view('staff.customers.index');
    }
}