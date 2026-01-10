<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Simple staff authentication
        $validStaff = [
            'username' => 'staff',
            'password' => 'password123'
        ];
        
        $username = $request->input('username');
        $password = $request->input('password');
        
        if ($username === $validStaff['username'] && $password === $validStaff['password']) {
            // Set cookie for staff authentication (valid for 8 hours)
            $response = redirect()->route('staff.dashboard')
                ->cookie('staff_authenticated', 'true', 480); // 8 hours
            
            return $response;
        }
        
        // If credentials are invalid
        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Invalid staff credentials');
    }
}