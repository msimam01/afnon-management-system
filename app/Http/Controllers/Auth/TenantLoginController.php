<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class TenantLoginController extends Controller
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 15;

    public function showLoginForm()
    {
        return view('auth.tenant-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        $this->ensureIsNotRateLimited($request);
        $this->performSecurityChecks($request);

        if (Auth::guard('tenant')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            Auth::shouldUse('tenant');
            $user = Auth::guard('tenant')->user();

            if ($user->status == 'active') {
                RateLimiter::clear($this->throttleKey($request));

                activity()
                    ->causedBy($user)
                    ->withProperties([
                        'tenant_id' => tenant('id'),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'login_time' => Carbon::now(),
                        'session_id' => $request->session()->getId(),
                        'remember_me' => $request->filled('remember'),
                    ])
                    ->log('logged in');

                $user->update(['last_login_at' => Carbon::now()]);

                // Redirect all authenticated users to a unified dashboard
                // Their role and permissions will determine what they can see and do
                ToastMagic::success('Welcome back! Login successful.');

                // Check if user has any valid role, if not, deny access
                if (!$user->hasAnyRole(['admin', 'agent', 'farmer'])) {
                    Auth::guard('tenant')->logout();
                    ToastMagic::error('Your account does not have the necessary permissions to access this system.');
                    return redirect()->route('tenant.login')->withErrors(['access' => 'Insufficient permissions.']);
                }

                // Redirect to unified dashboard - role-based content will be handled by the dashboard
                return redirect()->route('dashboard');
            } else {
                Auth::guard('tenant')->logout();
                ToastMagic::error('Your account has been deactivated!');
                return redirect()->route('tenant.login')->withErrors(['access' => 'Account deactivated.']);
            }
        }

        RateLimiter::hit($this->throttleKey($request), $this->decayMinutes * 60);

        activity()
            ->withProperties([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'reason' => 'Invalid credentials',
            ])
            ->log('login failed - invalid credentials');

        ToastMagic::error('The provided credentials are incorrect.');
        return back()->withErrors([
            'email' => 'The provided credentials are incorrect.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('tenant')->check()) {
            $user = Auth::guard('tenant')->user();
            activity()
                ->causedBy($user)
                ->withProperties([
                    'tenant_id' => tenant('id'),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'logout_time' => Carbon::now(),
                    'session_duration' => $this->calculateSessionDuration($user),
                ])
                ->log('logged out');
        }

        Auth::guard('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
ToastMagic::success('You have been logged out successfully.');
        return redirect()->route('tenant.login');
    }

    protected function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        $minutes = ceil($seconds / 60);

        activity()
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $request->input('email'),
                'lockout_duration' => $minutes,
            ])
            ->log('login rate limited');

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => $minutes,
            ]),
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    protected function performSecurityChecks(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $suspiciousPatterns = [
            'script', 'javascript:', 'vbscript:', 'onload', 'onerror',
            'eval(', 'expression(', 'url(', 'import(', '<script'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($email . $password, $pattern) !== false) {
                activity()
                    ->withProperties([
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'suspicious_pattern' => $pattern,
                        'email' => $email,
                    ])
                    ->log('suspicious login attempt detected');

                throw ValidationException::withMessages([
                    'email' => 'Invalid input detected.',
                ]);
            }
        }

        $userAgent = $request->userAgent();
        if (empty($userAgent) || strlen($userAgent) < 10) {
            activity()
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'email' => $email,
                ])
                ->log('suspicious user agent detected');
        }
    }

    protected function calculateSessionDuration($user)
    {
        if ($user->last_login_at) {
            return Carbon::now()->diffInMinutes($user->last_login_at);
        }
        return 0;
    }
}
