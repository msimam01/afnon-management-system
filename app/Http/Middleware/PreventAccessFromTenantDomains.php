<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::warning("Tenant domain '{$request->getHost()}' tried accessing central route.");
            ToastMagic::error('Access to central routes is not allowed from a tenant domain.');
            return redirect()->back();
        }

        return $next($request);
    }
}
