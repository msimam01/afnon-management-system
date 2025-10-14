<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ApplicationRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get tenant-specific rate limiting key
        $tenantId = function_exists('tenant') && tenant() ? tenant('id') : 'central';

        // Different rate limits based on request type
        $rateLimitKey = $this->getRateLimitKey($request, $tenantId);
        $maxAttempts = $this->getMaxAttempts($request);
        $decayMinutes = $this->getDecayMinutes($request);

        // Check if rate limit is exceeded
        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = ceil($seconds / 60);

            $errorMsg = "Too many attempts please wait a moment and try again.";
            ToastMagic::error($errorMsg);

            return back()->withInput()->withErrors([
                'phone' => $errorMsg
            ]);
        }

        // Hit the rate limiter
        RateLimiter::hit($rateLimitKey, $decayMinutes * 60);

        $response = $next($request);

        // If the application was successfully created, we can clear some rate limits
        if ($response->getStatusCode() === 302 &&
            $request->route()->getName() === 'applications.store' &&
            !session()->has('errors')) {
            // Don't clear the main rate limit, but we could implement success-based logic here
        }

        return $response;
    }

    /**
     * Get the rate limit key based on request type and user identification
     */
    private function getRateLimitKey(Request $request, string $tenantId): string
    {
        $baseKey = "app_submission_{$tenantId}";

        // For application creation, use multiple identifiers
        if ($request->route()->getName() === 'applications.store') {
            $identifiers = [];

            // Get current season ID from the request
            $seasonId = $request->input('season_id');
            
            // Include season ID in the key to scope rate limiting per season
            if ($seasonId) {
                $identifiers[] = 'season_' . $seasonId;
            }

            // Use phone number as primary identifier
            if ($request->has('phone')) {
                $identifiers[] = 'phone_' . $request->input('phone');
            } else {
                // If no phone, use IP as fallback
                $identifiers[] = 'ip_' . $request->ip();
            }

            return $baseKey . '_' . implode('_', $identifiers);
        }

        // For other requests, use IP-based limiting
        return $baseKey . '_ip_' . $request->ip();
    }

    /**
     * Get maximum attempts based on request type
     */
    private function getMaxAttempts(Request $request): int
    {
        if ($request->route()->getName() === 'applications.store') {
            // Allow 1 application submission per phone number per season
            return 1;
        }

        // For other requests (like viewing forms), allow more
        return 10;
    }

    /**
     * Get decay time in minutes based on request type
     */
    private function getDecayMinutes(Request $request): int
    {
        if ($request->route()->getName() === 'applications.store') {
            // 1 hour for application submissions
            return 60;
        }

        // 15 minutes for other requests
        return 15;
    }
}
