<?php

namespace App\Http\Controllers;

use App\Services\RoleRedirectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $roleRedirectionService;

    public function __construct(RoleRedirectionService $roleRedirectionService)
    {
        $this->roleRedirectionService = $roleRedirectionService;
    }

    /**
     * Show the unified dashboard based on user role and permissions
     */
    public function index()
    {
        $user = Auth::guard('tenant')->user();

        if (!$user) {
            return redirect()->route('tenant.login');
        }

        try {
            // Use the role redirection service to get the appropriate dashboard route
            $dashboardRoute = $this->roleRedirectionService->getDashboardRoute('tenant');

            return redirect()->route($dashboardRoute);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Dashboard redirection failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'user_roles' => $user->getRoleNames()->toArray(),
            ]);

            // If user has no recognized role, logout and redirect
            Auth::guard('tenant')->logout();
            return redirect()->route('tenant.login')->withErrors(['access' => 'No valid role assigned to your account.']);
        }
    }
}
