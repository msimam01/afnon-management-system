<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;

class EnsureTenantInitialized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // If domain is not a central domain, try initializing tenancy
            if (!in_array($request->getHost(), config('tenancy.central_domains'))) {
                tenancy()->initialize(
                    app(DomainTenantResolver::class)->resolve($request)
                );
            }
        } catch (TenantCouldNotBeIdentifiedOnDomainException $e) {
            // Do not crash the app if domain isn't a tenant — fallback to central
        }

        return $next($request);
    }
}
