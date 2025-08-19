<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SuperAdmin\Tenant;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check if we're in a tenant context
        if (tenancy()->initialized) {
            $tenant = tenant();
            
            // Check tenant status
            if (!$tenant->isActive()) {
                Log::warning("Access attempt to inactive tenant: {$tenant->id}", [
                    'tenant_id' => $tenant->id,
                    'status' => $tenant->status,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                
                // Return appropriate response based on tenant status
                return $this->handleInactiveTenant($tenant, $request);
            }
        }

        return $next($request);
    }

    /**
     * Handle inactive tenant access
     */
    private function handleInactiveTenant(Tenant $tenant, Request $request): Response
    {
        // If it's an API request, return JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Tenant is not available',
                'status' => $tenant->status,
                'message' => $this->getStatusMessage($tenant->status)
            ], 503);
        }

        // For web requests, show appropriate page based on status
        switch ($tenant->status) {
            case Tenant::STATUS_PENDING:
                return response()->view('tenant.status.pending', [
                    'tenant' => $tenant,
                    'message' => 'Your account is being set up. Please check back in a few minutes.'
                ], 503);
                
            case Tenant::STATUS_INACTIVE:
                return response()->view('tenant.status.deactivated', [
                    'tenant' => $tenant,
                    'reason' => $tenant->deactivation_reason,
                    'deactivated_at' => $tenant->deactivated_at
                ], 503);
                
            case Tenant::STATUS_SUSPENDED:
                return response()->view('tenant.status.suspended', [
                    'tenant' => $tenant,
                    'reason' => $tenant->deactivation_reason,
                    'suspended_at' => $tenant->deactivated_at
                ], 503);
                
            case Tenant::STATUS_FAILED:
                return response()->view('tenant.status.failed', [
                    'tenant' => $tenant,
                    'reason' => $tenant->deactivation_reason
                ], 503);
                
            default:
                return response()->view('tenant.status.unavailable', [
                    'tenant' => $tenant
                ], 503);
        }
    }

    /**
     * Get user-friendly status message
     */
    private function getStatusMessage(string $status): string
    {
        return match($status) {
            Tenant::STATUS_PENDING => 'Your account is being set up.',
            Tenant::STATUS_INACTIVE => 'Your account has been deactivated.',
            Tenant::STATUS_SUSPENDED => 'Your account has been suspended.',
            Tenant::STATUS_FAILED => 'There was an error setting up your account.',
            default => 'Your account is currently unavailable.'
        };
    }
}
