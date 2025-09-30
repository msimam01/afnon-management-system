@extends('emails.layouts.base')

@section('content')
<h1>🔐 Password Reset Request</h1>

<p>We received a request to reset your password for your AFNEN account. If you made this request, click the button below to reset your password.</p>

<div class="highlight-box">
    <h2>⚠️ Important Security Notice</h2>
    <p>This password reset link will expire in <strong>60 minutes</strong> for your security. If you didn't request this password reset, please ignore this email and your password will remain unchanged.</p>
</div>

<div class="button-container">
    <a href="{{ $url }}" class="button">
        🔑 Reset My Password
    </a>
</div>

<div class="info-box">
    <h3>🛡️ Security Tips</h3>
    <ul style="margin: 0; padding-left: 20px;">
        <li>Choose a strong password with at least 8 characters</li>
        <li>Include a mix of uppercase, lowercase, numbers, and symbols</li>
        <li>Don't use personal information like your name or birthdate</li>
        <li>Avoid using the same password for multiple accounts</li>
        <li>Consider using a password manager for better security</li>
    </ul>
</div>

<div style="background: #fef2f2; padding: 20px; border-radius: 8px; border-left: 4px solid #ef4444; margin: 25px 0;">
    <h3 style="color: #dc2626; margin: 0 0 10px 0;">🚨 Didn't Request This?</h3>
    <p style="margin: 0; color: #7f1d1d;">
        If you didn't request a password reset, someone may be trying to access your account.
        Please contact our support team immediately and consider changing your password if you can still log in.
    </p>
</div>

<div class="divider"></div>

<div style="background: #f8fafc; padding: 15px; border-radius: 6px; font-size: 12px; color: #64748b;">
    <strong>Technical Information:</strong><br>
    <strong>Request Time:</strong> {{ now()->format('F j, Y \a\t g:i A T') }}<br>
    <strong>IP Address:</strong> {{ request()->ip() ?? 'Not available' }}<br>
    <strong>User Agent:</strong> {{ request()->userAgent() ?? 'Not available' }}
</div>

<p style="margin-top: 30px; padding: 20px; background: #ecfdf5; border-radius: 8px; border-left: 4px solid #10b981;">
    <strong>💡 Need Help?</strong><br>
    If you're having trouble resetting your password or have any security concerns,
    please contact our support team at <a href="mailto:support@afnen.com.ng" style="color: #10b981;">support@afnen.com.ng</a>
    or call us for immediate assistance.
</p>

<p style="text-align: center; margin-top: 30px; font-size: 14px; color: #6b7280;">
    This link will expire in 60 minutes for your security. 🔒
</p>
@endsection

