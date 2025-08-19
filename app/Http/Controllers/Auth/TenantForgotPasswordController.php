<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
// use Illuminate\Auth\Events\PasswordReset;
use Stancl\Tenancy\Features\PasswordReset;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class TenantForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.tenant-forgot-password');
    }

    /**
     * Send a password reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Use the Password facade directly. Stancl's package overrides
        // this to be tenant-aware.
        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            ToastMagic::success('Password reset link sent!');
            return back()->with('status', __($response));
        }

        ToastMagic::error('We could not find an account with that email address.');
        return back()->withErrors(['email' => __($response)]);
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.tenant-reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Use the Password facade for resetting as well.
        $response = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            ToastMagic::success('Your password has been reset!');
            return redirect()->route('tenant.login')->with('status', __($response));
        }

        ToastMagic::error('This password reset token is invalid.');
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($response)]);
    }
}
