<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class AuthController extends Controller
{
    public function index(Request $request) {
        $client_settings = $request->client_settings;
        return view('login', compact('client_settings'));
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!empty($credentials['email']) && !empty($credentials['password'])) {
            if (auth()->attempt($credentials)) {
                return redirect()->route('admin.dashboard');
            }
        }
        // Authentication failed...
        return redirect('/')->with('error', 'Invalid credentials. Please try again.');
    }
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
