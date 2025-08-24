<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::guard('tenant')->check()) {
            activity()
                ->causedBy(Auth::guard('tenant')->user())
                ->withProperties([
                    'tenant_id'    => tenant('id'),
                    'ip_address'   => $request->ip(),
                    'user_agent'   => $request->userAgent(),
                    'url'          => $request->fullUrl(),
                    'method'       => $request->method(),
                    'input' => $request->except(['password', 'password_confirmation']), // avoid sensitive
                ])
                ->log('accessed ' . $request->path());
        }

        return $response;
    }
}
