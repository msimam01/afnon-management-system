<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.tenant-login'); // create this blade file
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $user = Auth::user();

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('agent')) {
                return redirect()->route('agent.dashboard');
            } elseif ($user->hasRole('farmer')) {
                return redirect()->route('farmer.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('tenant.login')->withErrors(['access' => 'Unauthorized role.']);
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('tenant.login');
    }
}

