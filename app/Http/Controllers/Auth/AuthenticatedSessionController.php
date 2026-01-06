<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = auth()->user();
            if ($user->usertype === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->usertype === 'staff') {
                return redirect()->route('staff.dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function create()
    {
        return view('auth.login');
    }

    // <-- ADD THIS METHOD
    public function destroy(Request $request)
    {
        Auth::logout(); // logout current user
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home'); // redirect to home page
    }
}
?>
