<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RoleRedirectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Carbon\Carbon;

class CustomLoginController extends Controller
{
    /**
     * Maximum login attempts allowed
     */
    protected $maxAttempts = 5;

    /**
     * Lockout duration in minutes
     */
    protected $decayMinutes = 15;

    protected $roleRedirectionService;

    public function __construct(RoleRedirectionService $roleRedirectionService)
    {
        $this->roleRedirectionService = $roleRedirectionService;
    }

    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // Enhanced validation with additional security rules
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        // Check if user is rate limited
        $this->ensureIsNotRateLimited($request);

        // Additional security checks
        $this->performSecurityChecks($request);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Clear rate limiter on successful login
            RateLimiter::clear($this->throttleKey($request));

            $user = Auth::user();

            if ($user->status == 'active') {
                // Enhanced activity logging with more details
                activity()
                    ->causedBy($user)
                    ->withProperties([
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'login_time' => Carbon::now(),
                        'session_id' => $request->session()->getId(),
                        'remember_me' => $request->boolean('remember'),
                    ])
                    ->log('logged in');

                // Update last login timestamp
                $user->update(['last_login_at' => Carbon::now()]);

                // Check if user has any valid role, if not, deny access
                if (!$this->roleRedirectionService->hasValidLoginRole($user, 'web')) {
                    Auth::logout();
                    ToastMagic::error('Your account does not have the necessary permissions to access this system.');
                    return redirect()->route('central.login.form')->withErrors(['access' => 'Insufficient permissions.']);
                }

                // Check if user needs to change password on first login
                if ($user->force_password_change) {
                    ToastMagic::info('Please change your password to continue.');
                    return redirect()->route('central.password.force.change');
                }

                // Get the appropriate dashboard route based on user's role
                $dashboardRoute = $this->roleRedirectionService->getDashboardRoute('web');
                ToastMagic::success('Welcome back! Login successful.');
                return redirect()->route($dashboardRoute);
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Log failed login attempt due to inactive account
                activity()
                    ->withProperties([
                        'email' => $credentials['email'],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'reason' => 'Account deactivated',
                    ])
                    ->log('login failed - account deactivated');

                ToastMagic::error('Your account has been deactivated! Please contact administrator.');
                return redirect()->route('central.login.form')->withErrors(['access' => 'Account deactivated.']);
            }
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($this->throttleKey($request), $this->decayMinutes * 60);

        // Log failed login attempt
        activity()
            ->withProperties([
                'email' => $credentials['email'],
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

    public function destroy(Request $request)
    {
        // Enhanced logout with security logging
        if (Auth::check()) {
            $user = Auth::user();

            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'logout_time' => Carbon::now(),
                    'session_duration' => $this->calculateSessionDuration($user),
                ])
                ->log('logged out');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        ToastMagic::success('You have been logged out successfully.');
        return redirect('/');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), $this->maxAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        $minutes = ceil($seconds / 60);

        // Log rate limiting event
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

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }

    /**
     * Perform additional security checks
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function performSecurityChecks(Request $request)
    {
        // Check for suspicious patterns
        $email = $request->input('email');
        $password = $request->input('password');

        // Check for common attack patterns
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

        // Check for unusual user agent patterns
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

    /**
     * Calculate session duration for logging
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    protected function calculateSessionDuration($user)
    {
        if ($user->last_login_at) {
            return Carbon::now()->diffInMinutes($user->last_login_at);
        }

        return 0;
    }
}
