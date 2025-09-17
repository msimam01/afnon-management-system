<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleRedirectionService
{
    /**
     * Get the appropriate dashboard route for the authenticated user
     *
     * @param string $guard The authentication guard to use
     * @return string The route name to redirect to
     * @throws \Exception If no valid role is found
     */
    public function getDashboardRoute(string $guard = null): string
    {
        $guard = $guard ?? $this->getCurrentGuard();
        $user = Auth::guard($guard)->user();

        if (!$user) {
            return $this->getDefaultRoute($guard);
        }

        $userRoles = $user->getRoleNames()->toArray();

        if (empty($userRoles)) {
            Log::warning('User has no roles assigned', [
                'user_id' => $user->id,
                'guard' => $guard,
            ]);
            return $this->getDefaultRoute($guard);
        }

        // Get the highest priority role based on hierarchy
        $primaryRole = $this->getPrimaryRole($userRoles, $guard);

        if (!$primaryRole) {
            Log::warning('User has no valid roles for guard', [
                'user_id' => $user->id,
                'user_roles' => $userRoles,
                'guard' => $guard,
            ]);
            return $this->getDefaultRoute($guard);
        }

        $dashboardConfig = config("roles.dashboards.{$primaryRole}");

        if (!$dashboardConfig) {
            Log::error('No dashboard configuration found for role', [
                'role' => $primaryRole,
                'guard' => $guard,
            ]);
            return $this->getDefaultRoute($guard);
        }

        return $dashboardConfig['route'];
    }

    /**
     * Check if user has any valid login roles for the given guard
     *
     * @param mixed $user User model with Spatie Permission traits
     * @param string $guard
     * @return bool
     */
    public function hasValidLoginRole($user, string $guard): bool
    {
        $validRoles = config("roles.valid_login_roles.{$guard}", []);

        if (empty($validRoles)) {
            return false;
        }

        return $user->hasAnyRole($validRoles);
    }

    /**
     * Get all valid login roles for a guard
     *
     * @param string $guard
     * @return array
     */
    public function getValidLoginRoles(string $guard): array
    {
        return config("roles.valid_login_roles.{$guard}", []);
    }

    /**
     * Get the primary role based on hierarchy
     *
     * @param array $userRoles
     * @param string $guard
     * @return string|null
     */
    protected function getPrimaryRole(array $userRoles, string $guard): ?string
    {
        $hierarchy = config("roles.hierarchy.{$guard}", []);

        if (empty($hierarchy)) {
            return $userRoles[0] ?? null;
        }

        // Sort roles by hierarchy priority (lower number = higher priority)
        $sortedRoles = collect($userRoles)
            ->filter(fn($role) => isset($hierarchy[$role]))
            ->sortBy(fn($role) => $hierarchy[$role])
            ->values()
            ->toArray();

        return $sortedRoles[0] ?? null;
    }

    /**
     * Get the current authentication guard
     *
     * @return string
     */
    protected function getCurrentGuard(): string
    {
        // Check if we're in a tenant context
        if (tenant()) {
            return 'tenant';
        }

        return 'web';
    }

    /**
     * Get the default route for a guard
     *
     * @param string $guard
     * @return string
     */
    protected function getDefaultRoute(string $guard): string
    {
        return config("roles.defaults.{$guard}", 'login');
    }

    /**
     * Get dashboard configuration for a specific role
     *
     * @param string $role
     * @return array|null
     */
    public function getDashboardConfig(string $role): ?array
    {
        return config("roles.dashboards.{$role}");
    }

    /**
     * Get all available dashboard configurations
     *
     * @return array
     */
    public function getAllDashboardConfigs(): array
    {
        return config('roles.dashboards', []);
    }
}
