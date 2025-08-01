<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class TenantLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.tenant-login'); // 
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
        ToastMagic::error('The provided credentials are incorrect.');
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('tenant.login');
    }
}

