<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CustomLoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            activity()
                ->causedBy($user)
                ->log('logged in');
            if ($user->hasRole('super-admin')) {
                return redirect()->route('superadmin.dashboard');
            }

        }
        ToastMagic::error('The provided credentials are incorrect.');
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }
    public function destroy(Request $request)
    {
        // Log the logout action as well
        if (Auth::check()) {
            activity()
                ->causedBy(Auth::user())
                ->log('logged out');
        }
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
