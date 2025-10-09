<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Carbon\Carbon;

class CentralForcePasswordChangeController extends Controller
{
    /**
     * Show the force password change form.
     */
    public function show()
    {
        // Ensure only authenticated central users who need password change can access this
        if (!Auth::check() || !Auth::user()->force_password_change) {
            return redirect()->route('central.login.form');
        }

        return view('auth.central-force-password-change');
    }

    /**
     * Handle the force password change.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Ensure only the authenticated central user who needs password change can update
        if (!$user || !$user->force_password_change) {
            ToastMagic::error('Unauthorized access.');
            return redirect()->route('central.login.form');
        }

        $validated = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        // Update the password and mark as changed for central user
        \App\Models\User::where('id', $user->id)->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => Carbon::now(),
            'force_password_change' => false,
        ]);

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

        // Redirect to appropriate dashboard based on role
        $roleRedirectionService = app(\App\Services\RoleRedirectionService::class);
        $dashboardRoute = $roleRedirectionService->getDashboardRoute('web');

        return redirect()->route($dashboardRoute);
    }
}
