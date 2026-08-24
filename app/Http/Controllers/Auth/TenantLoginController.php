<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Spatie\Activitylog\Models\Activity;

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
            if ($user->status == 'active') {
                // Log the successful login right here
                activity()
                    ->causedBy($user)
                    ->log('logged in');

                // Redirect based on role
                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('agent')) {
                    return redirect()->route('agent.dashboard');
                } else {
                    Auth::logout();
                    ToastMagic::error('Unauthorized role');
                    return redirect()->route('tenant.login')->withErrors(['access' => 'Unauthorized role.']);
                }

            } else {
                ToastMagic::error('Your account has been deactivated!');
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
        // Log the logout action as well
        if (Auth::check()) {
            activity()
                ->causedBy(Auth::user())
                ->log('logged out');
        }

        Auth::logout();
        return redirect()->route('tenant.login');
    }
}

