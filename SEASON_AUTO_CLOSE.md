# Automatic Season Closing

This document describes the automatic season closing feature that prevents expired seasons from remaining open indefinitely.

## Overview

Seasons in the AfnOn Management System have fixed start and end dates. To ensure system integrity and prevent users from continuing to submit applications for outdated seasons, the system automatically closes seasons when their end date arrives.

## How It Works

### Scheduled Command
The system runs a scheduled command every day at 12:01 AM (00:01) that:
1. Checks all tenants in the system
2. For each tenant, finds seasons with status 'open' where `end_date` is in the past
3. Automatically updates these seasons' status to 'closed'

### Multi-Tenant Support
Since AfnOn uses a multi-tenant architecture, the command processes each tenant separately, ensuring that:
- Only tenant-specific seasons are accessed
- Each tenant's database context is properly initialized
- Actions are logged per tenant

## Usage

### Manual Execution
You can manually run the auto-close command using Artisan:

```bash
# Check what would be closed (dry run)
php artisan seasons:auto-close-expired --dry-run

# Close expired seasons for all tenants
php artisan seasons:auto-close-expired

# Close expired seasons for a specific tenant
php artisan seasons:auto-close-expired 1
```

### Scheduling
The command is automatically scheduled to run daily. No additional cron setup is required beyond standard Laravel scheduling configuration.

To ensure Laravel's scheduler runs properly, add this to your server's crontab:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Logging

All automatic season closures are logged with the following information:
- Season name and UUID
- Tenant information
- Timestamp of closure
- Log level: INFO

Example log entry:
```
[2025-10-09 00:01:00] local.INFO: Automatically closed expired season: Dry Season 2024 (UUID: abc123) for tenant bauchi agri
```

## Safety Features

- **Without overlapping**: Prevents multiple instances of the command from running simultaneously
- **Run in background**: Doesn't block other system processes
- **Dry run support**: Test functionality without making changes
- **Tenant isolation**: Each tenant's data is processed separately and safely

## Configuration

The command can be scheduled at different times by modifying `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('seasons:auto-close-expired')
        ->daily()
        ->at('00:01') // Change time here
        ->withoutOverlapping()
        ->runInBackground();
}
```

## Monitoring

Monitor the automatic closure process by:
1. Checking Laravel logs for closure activity
2. Reviewing season status changes in the admin dashboard
3. Using the dry-run option to preview upcoming closures

## Impact on Users

When a season is automatically closed:
- Users can no longer submit new applications for that season
- Existing applications remain available for viewing and verification
- Admins can still reopen the season manually if needed
- The season appears as "closed" in the season management interface

## Troubleshooting

### Command Not Running
- Verify Laravel scheduler is set up correctly
- Check server timezone settings match application timezone
- Ensure application server time is accurate

### Seasons Not Closing
- Confirm season end dates are set correctly
- Check if season status is 'open' (not already closed)
- Verify tenant database connections are working

### Database Issues
- The command uses proper tenant context switching
- All operations are logged for debugging
- Database transactions ensure data integrity
