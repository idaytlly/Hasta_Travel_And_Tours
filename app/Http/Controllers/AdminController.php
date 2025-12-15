<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // app/Http/Controllers/AdminController.php

public function index()
{
    // 1. Check if a user is authenticated
    if (Auth::check()) 
    {
        // 2. Get the usertype and normalize it to lowercase
        $usertype = strtolower(Auth::user()->usertype); 
        
        // 3. Check the user type and redirect to the correct view
        if ($usertype === 'admin')
        {
            return view('admin.index'); 
        } 
        else if ($usertype === 'user')
        {
            return view('dashboard');
        }
        else if ($usertype === 'staff')
        {
            return view('staff.index');
        }
        else 
        {
            return redirect()->back();
        }
    }
    return redirect()->route('login'); 
    }
}
    

