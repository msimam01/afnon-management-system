<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class ForcePasswordChangeController extends Controller
{
    /**
     * Show the force password change form.
     */
    public function show(Request $request)
    {
        $user = null;
        $loginRoute = 'central.login.form';

        // Check if this is a tenant request
        if (Auth::guard('tenant')->check()) {
            $user = Auth::guard('tenant')->user();
            $loginRoute = 'tenant.login';
        } elseif (Auth::check()) {
            $user = Auth::user();
            $loginRoute = 'central.login.form';
        }

        // Ensure only authenticated users who need password change can access this
        if (!$user || !$user->force_password_change) {
            return redirect()->route($loginRoute);
        }

        return view('auth.force-password-change', compact('user'));
    }

    /**
     * Handle the force password change.
     */
    public function update(Request $request)
    {
        $loginRoute = 'central.login.form';
        $dashboardRoute = 'central.dashboard'; // fallback

        // Check if this is a tenant request
        if (Auth::guard('tenant')->check()) {
            $user = Auth::guard('tenant')->user();
            $loginRoute = 'tenant.login';
            $dashboardRoute = 'dashboard'; // tenant dashboard route
        } elseif (Auth::check()) {
            $user = Auth::user();
            $loginRoute = 'central.login.form';
            $dashboardRoute = 'central.dashboard';
        } else {
            ToastMagic::error('Unauthorized access.');
            return redirect()->route($loginRoute);
        }

        // Ensure only the authenticated user who needs password change can update
        if (!$user || !$user->force_password_change) {
            ToastMagic::error('Unauthorized access.');
            return redirect()->route($loginRoute);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Update the password and mark as changed
        if (Auth::guard('tenant')->check()) {
            \App\Models\Tenant\User::where('id', $user->id)->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => Carbon::now(),
                'force_password_change' => false,
            ]);
        } else {
            \App\Models\User::where('id', $user->id)->update([
                'password' => Hash::make($validated['password']),
                'password_changed_at' => Carbon::now(),
                'force_password_change' => false,
            ]);
        }

        // Log the password change activity (simplified approach)
        try {
            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'password_changed_at' => Carbon::now(),
                ])
                ->log('password changed (forced on first login)');
        } catch (\Exception $e) {
            // Log activity failed, but continue with password change
            Log::info('Failed to log password change activity: ' . $e->getMessage());
        }

        ToastMagic::success('Password changed successfully! Welcome to the system.');

        // Redirect to appropriate dashboard based on user type
        return redirect()->route($dashboardRoute);
    }
}
