<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail; // Optional kalau nak send email

class ContactController extends Controller
{
    // Show contact form
    public function index()
    {
        return view('contactus'); // pastikan file blade contact.blade.php wujud
    }

    // Handle form submission
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'comments' => 'required|string',
        ]);

        // Optional: send email or save to DB

        return back()->with('success', 'Your message has been sent!');
    }
}
