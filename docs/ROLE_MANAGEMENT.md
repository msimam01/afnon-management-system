# Role Management System

## Overview

The system now uses a configuration-driven approach for role-based redirections, making it easy to add new roles without modifying controller code.

## Adding New Roles

### 1. Update Role Configuration

Edit `/config/roles.php` to add your new role:

```php
'dashboards' => [
    // ... existing roles ...
    
    'new-role' => [
        'route' => 'new-role.dashboard',
        'guard' => 'tenant', // or 'web' for central domain
        'description' => 'New Role Dashboard',
    ],
],

'valid_login_roles' => [
    'web' => ['super-admin'],
    'tenant' => ['system-admin', 'admin', 'agent', 'farmer', 'new-role'], // Add here
],

'hierarchy' => [
    'tenant' => [
        'system-admin' => 1,
        'admin' => 2,
        'agent' => 3,
        'farmer' => 4,
        'new-role' => 5, // Add with appropriate priority
    ],
    // ...
],
```

### 2. Create Role in Database

Add the role to your seeder or create it manually:

```php
// In TenantSeeder.php or similar
Role::create([
    'name' => 'new-role',
    'guard_name' => 'tenant',
    'tenant_id' => tenant('id')
]);
```

### 3. Create Dashboard Route

Add the dashboard route in your routes file:

```php
// In routes/tenant.php
Route::middleware(['auth:tenant', 'role:new-role'])->prefix('new-role')->name('new-role.')->group(function () {
    Route::get('dashboard', [NewRoleDashboardController::class, 'index'])->name('dashboard');
});
```

### 4. Create Dashboard Controller

```php
<?php

namespace App\Http\Controllers;

class NewRoleDashboardController extends Controller
{
    public function index()
    {
        return view('new-role.dashboard');
    }
}
```

### 5. Clear Configuration Cache

```bash
php artisan config:cache
```

## How It Works

1. **Login Process**: When a user logs in, the system checks if they have any valid roles defined in `valid_login_roles`
2. **Role Hierarchy**: If a user has multiple roles, they're redirected based on the hierarchy (lower number = higher priority)
3. **Dashboard Redirection**: The system looks up the dashboard route from the configuration
4. **Fallback**: If no valid role is found, the user is logged out and redirected to login

## Benefits

- ✅ **Scalable**: Add new roles without touching controller code
- ✅ **Maintainable**: All role logic centralized in configuration
- ✅ **Flexible**: Easy to change redirects or add new roles
- ✅ **Hierarchical**: Support for users with multiple roles
- ✅ **Type-safe**: Proper error handling and logging

## Configuration Options

### Dashboard Configuration
- `route`: The route name to redirect to
- `guard`: Authentication guard ('web' or 'tenant')
- `description`: Human-readable description

### Valid Login Roles
Defines which roles are allowed to login for each guard.

### Hierarchy
Defines role priority when users have multiple roles (lower number = higher priority).

### Defaults
Fallback routes when no valid role is found.
