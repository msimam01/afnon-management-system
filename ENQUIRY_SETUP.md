# Enquiry System Setup Guide

## Overview
This document provides instructions for setting up the enquiry/contact form system in your Laravel application.

## Features Implemented

### 1. Database Storage
- ✅ Created `enquiries` table with fields: id, name, email, phone, subject, message, ip_address, user_agent, is_spam, read_at, created_at, updated_at
- ✅ Works for both tenant and central databases
- ✅ Includes proper indexes for performance

### 2. Email Sending
- ✅ Laravel Mail system integration
- ✅ Gmail SMTP configuration
- ✅ EnquiryMail mailable class
- ✅ Professional email template

### 3. Admin Dashboard
- ✅ Admin panel section to view all enquiries
- ✅ Table with columns: Name, Email, Subject, Message, Date, Status
- ✅ Filters for date range and search by email/subject
- ✅ Mark as read/unread, spam/not spam functionality
- ✅ Delete enquiries

### 4. Validation & UX
- ✅ Required field validation
- ✅ Email format validation
- ✅ Success/error messages using ToastMagic
- ✅ Form preserves input on validation errors

### 5. Spam Protection
- ✅ Honeypot field (hidden field that bots fill)
- ✅ Spam keyword detection
- ✅ Excessive links detection
- ✅ Excessive caps detection

## Configuration

### 1. Gmail SMTP Setup

Add these settings to your `.env` file:

```env
# Mail Configuration for Gmail SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
MAIL_ADMIN_EMAIL=admin@afnon.com.ng
```

### 2. Gmail App Password Setup

1. Go to your Google Account settings
2. Enable 2-Factor Authentication
3. Go to "App passwords" section
4. Generate a new app password for "Mail"
5. Use this password in `MAIL_PASSWORD` (not your regular Gmail password)

### 3. Database Migration

The migration has been created and should be run:

```bash
# Run migration on central database
php artisan migrate

# Run migration on all tenant databases
php artisan tenants:migrate --path=database/migrations/2025_09_19_193413_create_enquiries_table.php
```

**Note**: In multi-tenant applications, you need to run migrations on both central and tenant databases. The `tenants:migrate` command ensures the enquiries table is created in all tenant databases.

## Usage

### 1. Contact Form
The contact form is now functional on the landing page (`/`) and will:
- Validate all required fields
- Check for spam
- Store enquiry in database
- Send email notification to admin
- Show success message

### 2. Admin Panel

#### Central Admin (Super Admin)
- Access: `/super-admin/enquiries`
- View all enquiries from central domain
- Filter by status, date, search terms
- Mark as spam/not spam
- Delete enquiries

#### Tenant Admin
- Access: `/admin/enquiries` (within tenant domain)
- View enquiries specific to that tenant
- Same functionality as central admin

### 3. Email Notifications
When a new enquiry is submitted (and not marked as spam):
- Email is sent to the admin email address
- Subject: "New Enquiry: [Subject]"
- Contains all enquiry details
- Includes link to view in admin panel

## File Structure

```
app/
├── Http/Controllers/EnquiryController.php
├── Mail/EnquiryMail.php
└── Models/Enquiry.php

database/migrations/
└── 2025_09_19_193413_create_enquiries_table.php

resources/views/
├── admin/enquiries/
│   ├── index.blade.php
│   └── show.blade.php
├── emails/
│   └── enquiry.blade.php
└── welcome.blade.php (updated contact form)

routes/
├── web.php (central routes)
└── tenant.php (tenant routes)
```

## Permissions Required

For the admin panel to work, ensure these permissions exist:

### Central (Super Admin)
- `manage_central_enquiries`
- `read_central_enquiry`
- `delete_central_enquiry`

### Tenant Admin
- `manage_enquiries`
- `read_enquiry`
- `delete_enquiry`

## Testing

1. Visit the landing page
2. Fill out the contact form
3. Submit the form
4. Check that:
   - Success message appears
   - Enquiry is stored in database
   - Email is sent to admin
   - Admin can view enquiry in dashboard

## Security Features

1. **Honeypot Field**: Hidden field that bots typically fill
2. **Spam Detection**: Multiple algorithms to detect spam
3. **Input Validation**: Server-side validation of all fields
4. **CSRF Protection**: Laravel's built-in CSRF protection
5. **Rate Limiting**: Can be added to routes if needed

## Customization

### Email Template
Edit `resources/views/emails/enquiry.blade.php` to customize the email format.

### Spam Detection
Modify the `detectSpam()` method in `EnquiryController.php` to add more spam detection rules.

### Form Fields
Update the contact form in `welcome.blade.php` to add/remove fields as needed.

### Admin Views
Customize the admin views in `resources/views/admin/enquiries/` to match your design.

## Troubleshooting

### Email Not Sending
1. Check Gmail SMTP settings in `.env`
2. Verify Gmail app password is correct
3. Check Laravel logs for email errors
4. Test with `php artisan tinker`:
   ```php
   Mail::raw('Test email', function($msg) {
       $msg->to('your-email@gmail.com')->subject('Test');
   });
   ```

### Form Not Working
1. Check routes are properly defined
2. Verify CSRF token is included
3. Check browser console for JavaScript errors
4. Verify form action URL is correct

### Admin Panel Access Issues
1. Check user has required permissions
2. Verify routes are in correct middleware groups
3. Check if user is logged in with correct guard

## Production Considerations

1. **Queue Emails**: Consider using queues for email sending
2. **Rate Limiting**: Add rate limiting to prevent spam
3. **Backup**: Regular database backups
4. **Monitoring**: Monitor email delivery rates
5. **Security**: Regular security updates

## Support

For issues or questions, check:
1. Laravel documentation
2. Application logs
3. Email service provider documentation
4. Database connection settings
