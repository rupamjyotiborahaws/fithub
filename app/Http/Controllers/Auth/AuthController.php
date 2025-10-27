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

    public function showLinkRequestForm(Request $request)
    {
        $client_settings = $request->client_settings;
        $email = $client_settings['email'] ?? '';
        $masked_email = '';
        if (!empty($email)) {
            $email_parts = explode('@', $email);
            $local_part = $email_parts[0];
            $domain_part = $email_parts[1];
            $local_length = strlen($local_part);
            if ($local_length <= 2) {
                $masked_email = str_repeat('*', $local_length) . '@' . $domain_part;
            } else {
                $masked_email = substr($local_part, 0, 1) . str_repeat('*', $local_length - 2) . substr($local_part, -1) . '@' . $domain_part;
            }
        }
        $email = $masked_email;
        return view('admin.forgot_password', compact('client_settings', 'email'));
    }
}
