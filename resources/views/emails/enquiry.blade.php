@extends('emails.layouts.base')

@section('content')
<h1>New Enquiry Received</h1>

<p>You have received a new enquiry from your website contact form. Please review the details below and take appropriate action.</p>

<div class="highlight-box">
    <h2>📧 Enquiry Details</h2>

    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Name:</div>
            <div class="info-value">{{ $enquiry->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">
                <a href="mailto:{{ $enquiry->email }}" style="color: #10b981; text-decoration: none;">{{ $enquiry->email }}</a>
            </div>
        </div>
        @if($enquiry->phone)
        <div class="info-row">
            <div class="info-label">Phone:</div>
            <div class="info-value">
                <a href="tel:{{ $enquiry->phone }}" style="color: #10b981; text-decoration: none;">{{ $enquiry->phone }}</a>
            </div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Subject:</div>
            <div class="info-value"><strong>{{ $enquiry->subject }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Date:</div>
            <div class="info-value">{{ $enquiry->formatted_created_at }}</div>
        </div>
    </div>
</div>

<div class="info-box">
    <h3>💬 Message</h3>
    <p style="background: #f8fafc; padding: 15px; border-radius: 6px; border-left: 3px solid #10b981; margin: 0; font-style: italic;">
        "{{ $enquiry->message }}"
    </p>
</div>

<div class="divider"></div>

<div style="background: #f1f5f9; padding: 15px; border-radius: 6px; font-size: 12px; color: #64748b;">
    <strong>Technical Details:</strong><br>
    <strong>IP Address:</strong> {{ $enquiry->ip_address }}<br>
    <strong>User Agent:</strong> {{ Str::limit($enquiry->user_agent, 100) }}
</div>

<div class="button-container">
    <a href="{{ config('app.url') }}/admin/enquiries/{{ $enquiry->id }}" class="button">
        📋 View Full Enquiry Details
    </a>
</div>

<p style="margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
    <strong>💡 Quick Actions:</strong><br>
    • Reply directly to the customer using the email address above<br>
    • Mark as read/unread in the admin panel<br>
    • Flag as spam if necessary<br>
    • Delete if it's not relevant
</p>
@endsection

