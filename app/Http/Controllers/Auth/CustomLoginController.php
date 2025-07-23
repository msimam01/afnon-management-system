<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->hasRole('super-admin')) {
                return redirect()->route('superadmin.dashboard');
            } elseif ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('agent')) {
                return redirect()->route('agent.dashboard');
            } elseif ($user->hasRole('farmer')) {
                return redirect()->route('farmer.dashboard');
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }
}
