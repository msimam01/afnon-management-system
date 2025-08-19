<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user status is not active
            if ($user->status !== 'active') {
                // Log the automatic logout
                activity()
                    ->causedBy($user)
                    ->log('automatically logged out due to account deactivation');
                
                // Logout the user
                Auth::logout();
                
                // Invalidate the session
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Show error message
                ToastMagic::error('Your account has been deactivated. You have been logged out.');
                
                // Redirect to appropriate login page based on domain
                $centralDomains = config('tenancy.central_domains');
                if (in_array($request->getHost(), $centralDomains)) {
                    return redirect()->route('central.login.form');
                } else {
                    return redirect()->route('tenant.login');
                }
            }
        }

        return $next($request);
    }
}
