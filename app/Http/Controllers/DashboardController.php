<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the unified dashboard based on user role and permissions
     */
    public function index()
    {
        $user = Auth::guard('tenant')->user();

        if (!$user) {
            return redirect()->route('tenant.login');
        }

        // Redirect based on user's primary role to their respective dashboard
        // This maintains the existing dashboard functionality while allowing for unified login
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('agent')) {
            return redirect()->route('agent.dashboard');
        } elseif ($user->hasRole('farmer')) {
            // For future farmer dashboard - for now redirect to applications
            return redirect()->route('applications.create');
        }

        // If user has no recognized role, logout and redirect
        Auth::guard('tenant')->logout();
        return redirect()->route('tenant.login')->withErrors(['access' => 'No valid role assigned to your account.']);
    }
}
