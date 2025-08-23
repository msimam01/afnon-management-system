<?php

namespace App\Http\Middleware;

use Closure;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('tenant')->check()) {
            $user = Auth::guard('tenant')->user();

            if ($user->status !== 'active') {
                activity()->causedBy($user)->log('automatically logged out due to account deactivation');

                Auth::guard('tenant')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                ToastMagic::error('Your account has been deactivated. You have been logged out.');

                return redirect()->route('tenant.login');
            }
        }

        return $next($request);
    }
}
