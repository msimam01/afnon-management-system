<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['access' => 'Authentication required.']);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if user has the required permission
        if (!$user->can($permission)) {
            // Log unauthorized access attempt
            \Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'permission' => $permission,
                'route' => $request->route()->getName(),
                'ip' => $request->ip(),
            ]);

            // Return 403 Forbidden response
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
