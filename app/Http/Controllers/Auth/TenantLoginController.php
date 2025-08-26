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

        if (Auth::guard('tenant')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            Auth::shouldUse('tenant');
            $user = Auth::guard('tenant')->user();

            if ($user->status == 'active') {
                activity()
                    ->causedBy($user)
                    ->withProperties([
                        'tenant_id' => tenant('id'),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'additional_info' => $extraInfo ?? null,
                    ])
                    ->log('logged in');


                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('agent')) {
                    return redirect()->route('agent.dashboard');
                } else {
                    Auth::guard('tenant')->logout();
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
        if (Auth::guard('tenant')->check()) {
            activity()
                    ->causedBy(Auth::guard('tenant')->user())
                    ->withProperties([
                        'tenant_id' => tenant('id'),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                        'additional_info' => $extraInfo ?? null,
                    ])
                    ->log('logged out');
        }

        Auth::guard('tenant')->logout();
        return redirect()->route('tenant.login');
    }
}
