<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Symfony\Component\HttpFoundation\Response;

class PreventAccessFromTenantDomains
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get central domains from config
        $centralDomains = config('tenancy.central_domains');

        // If current domain is NOT in central domains, it's a tenant domain
        if (!in_array($request->getHost(), $centralDomains)) {
            // Auto logout user
            Auth::logout();

            // Log the event
            Log::warning("Tenant domain '{$request->getHost()}' tried accessing central route. User logged out.");

            // Redirect with message
            ToastMagic::error('Access denied. You have been logged out for trying to access a restricted route.');
            return redirect()->route('tenant.login');
        }

        return $next($request);
    }
}
