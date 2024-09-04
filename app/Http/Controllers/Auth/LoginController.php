<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Retrieve the user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if the user exists and is active
        if ($user && $user->is_active == 0) {
            return Redirect::back()->withErrors([
                'message' => 'User no longer access!'
            ])->withInput();
        }
        if ($user && $user->is_active == 1 && Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard')); // Use intended here
        }

        // Handle invalid login attempts
        return Redirect::back()->withErrors([
            'message' => 'Invalid username or password!'
        ])->withInput();
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect("/login");
    }
}
