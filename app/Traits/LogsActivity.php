<?php

namespace App\Traits;

use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log user activity with comprehensive details
     */
    public function logActivity(string $description, string $logName = 'default', array $properties = [], $subject = null): void
    {
        $properties = array_merge([
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'tenant_id' => $this->getCurrentTenantId(),
        ], $properties);

        activity($logName)
            ->causedBy(auth()->user())
            ->performedOn($subject)
            ->withProperties($properties)
            ->log($description);
    }

    /**
     * Log authentication events
     */
    public function logAuth(string $action, array $properties = []): void
    {
        $this->logActivity(
            "User {$action}",
            'authentication',
            array_merge([
                'action' => $action,
                'user_id' => auth()->id(),
                'user_email' => auth()->user()?->email,
            ], $properties)
        );
    }

    /**
     * Log user management actions
     */
    public function logUserManagement(string $action, $targetUser = null, array $properties = []): void
    {
        $description = "User management: {$action}";
        
        if ($targetUser) {
            $description .= " for {$targetUser->name} ({$targetUser->email})";
            $properties['target_user_id'] = $targetUser->id;
            $properties['target_user_email'] = $targetUser->email;
        }

        $this->logActivity(
            $description,
            'user_management',
            array_merge([
                'action' => $action,
            ], $properties),
            $targetUser
        );
    }

    /**
     * Log tenant management actions (for super admin)
     */
    public function logTenantManagement(string $action, $tenant = null, array $properties = []): void
    {
        $description = "Tenant management: {$action}";
        
        if ($tenant) {
            $description .= " for tenant {$tenant->id} ({$tenant->name})";
            $properties['target_tenant_id'] = $tenant->id;
            $properties['target_tenant_name'] = $tenant->name;
        }

        $this->logActivity(
            $description,
            'tenant_management',
            array_merge([
                'action' => $action,
            ], $properties),
            $tenant
        );
    }

    /**
     * Log loan management actions
     */
    public function logLoanManagement(string $action, $loan = null, array $properties = []): void
    {
        $description = "Loan management: {$action}";
        
        if ($loan) {
            $description .= " for loan #{$loan->id}";
            $properties['loan_id'] = $loan->id;
            $properties['loan_status'] = $loan->status ?? null;
            $properties['farmer_id'] = $loan->farmer_id ?? null;
        }

        $this->logActivity(
            $description,
            'loan_management',
            array_merge([
                'action' => $action,
            ], $properties),
            $loan
        );
    }

    /**
     * Log system events
     */
    public function logSystem(string $description, array $properties = []): void
    {
        $this->logActivity(
            $description,
            'system',
            $properties
        );
    }

    /**
     * Get current tenant ID
     */
    private function getCurrentTenantId(): ?string
    {
        try {
            if (function_exists('tenant') && tenant()) {
                return tenant('id');
            }
            return 'central';
        } catch (\Exception $e) {
            return 'central';
        }
    }
}
