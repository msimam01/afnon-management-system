<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Role-based Dashboard Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines how users with different roles should be
    | redirected after login. This makes it easy to add new roles without
    | modifying controller code.
    |
    */

    'dashboards' => [
        // Super Admin (Central Domain)
        'super-admin' => [
            'route' => 'superadmin.dashboard',
            'guard' => 'web',
            'description' => 'Super Administrator Dashboard',
        ],

        // Tenant Roles
        'admin' => [
            'route' => 'admin.dashboard',
            'guard' => 'tenant',
            'description' => 'Administrator Dashboard',
        ],
        'agent' => [
            'route' => 'agent.dashboard',
            'guard' => 'tenant',
            'description' => 'Field Agent Dashboard',
        ],
        'farmer' => [
            'route' => 'applications.create', // Temporary redirect until farmer dashboard is ready
            'guard' => 'tenant',
            'description' => 'Farmer Dashboard',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Valid Login Roles
    |--------------------------------------------------------------------------
    |
    | Define which roles are allowed to login to the system.
    | This replaces hard-coded role checks in login controllers.
    |
    */

    'valid_login_roles' => [
        'web' => ['super-admin'],
        'tenant' => ['admin', 'agent', 'farmer'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Hierarchy
    |--------------------------------------------------------------------------
    |
    | Define role hierarchy for fallback redirection.
    | If a user has multiple roles, they'll be redirected based on priority.
    |
    */

    'hierarchy' => [
        'tenant' => [
            'admin' => 1,
            'agent' => 2,
            'farmer' => 3,
        ],
        'web' => [
            'super-admin' => 1,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Redirects
    |--------------------------------------------------------------------------
    |
    | Fallback routes when no valid role is found or for edge cases.
    |
    */

    'defaults' => [
        'web' => 'central.login.form',
        'tenant' => 'tenant.login',
    ],
];
