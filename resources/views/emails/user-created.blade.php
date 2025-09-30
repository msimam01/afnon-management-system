@extends('emails.layouts.base')

@section('content')
<h1>🎉 Welcome to AFNEN, {{ $user->name }}!</h1>

<p>Your account has been successfully created and you're now part of the AFNEN community. We're excited to have you on board!</p>

<div class="highlight-box">
    <h2>🔐 Your Account Details</h2>
    <p>Please keep these credentials safe and secure. We recommend changing your password immediately after your first login.</p>

    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Full Name:</div>
            <div class="info-value"><strong>{{ $user->name }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Email Address:</div>
            <div class="info-value">{{ $user->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Temporary Password:</div>
            <div class="info-value" style="font-family: monospace; background: #f1f5f9; padding: 8px; border-radius: 4px; color: #e11d48; font-weight: bold;">{{ $password }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Account Created:</div>
            <div class="info-value">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</div>
        </div>
    </div>
</div>

<div class="button-container">
    <a href="{{ url('/') }}" class="button">
        🚀 Access Your Account
    </a>
</div>

<div class="info-box">
    <h3>🛡️ Security Recommendations</h3>
    <ul style="margin: 0; padding-left: 20px;">
        <li><strong>Change your password immediately</strong> after your first login</li>
        <li>Use a strong, unique password with at least 8 characters</li>
        <li>Never share your login credentials with anyone</li>
        <li>Log out from shared or public computers</li>
        <li>Contact us immediately if you notice any suspicious activity</li>
    </ul>
</div>

<div class="highlight-box">
    <h2>🌱 What's Next?</h2>
    <p>Now that you're part of AFNEN, here's what you can do:</p>
    <ul style="margin: 0; padding-left: 20px;">
        <li>Complete your profile with additional information</li>
        <li>Explore our farmer resources and tools</li>
        <li>Connect with other farmers in your region</li>
        <li>Access exclusive agricultural programs and benefits</li>
        <li>Stay updated with the latest farming news and events</li>
    </ul>
</div>

<p style="margin-top: 30px; padding: 20px; background: #ecfdf5; border-radius: 8px; border-left: 4px solid #10b981;">
    <strong>💡 Need Help?</strong><br>
    If you have any questions or need assistance, don't hesitate to reach out to our support team. We're here to help you make the most of your AFNEN membership!
</p>

<p style="text-align: center; margin-top: 30px; font-size: 14px; color: #6b7280;">
    Welcome to the future of farming in Nigeria! 🌾
</p>
@endsection
