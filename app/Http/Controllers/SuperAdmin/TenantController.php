<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\Tenant;
use App\Http\Controllers\Controller;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Jobs\CreateTenantJob;

class TenantController extends Controller
{
    use LogsActivity;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')
            ->orderBy('created_at', 'desc')
            ->paginate(20); // Pagination for better performance
        return view('super-admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('super-admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|alpha_dash|unique:tenants,id|max:50',
            'name' => 'required|string|max:255',
            'domain' => 'required|url',
            'description' => 'nullable|string|max:500',
        ]);

        $host = parse_url($request->domain, PHP_URL_HOST);

        // Validate domain format for subdomains
        if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.afnon\.com$/', $host)) {
            ToastMagic::error('Domain must be a valid subdomain of afnon.com (e.g., kano.afnon.com)');
            return redirect()->back()->withInput();
        }

        // Check if domain already exists
        if (\Stancl\Tenancy\Database\Models\Domain::where('domain', $host)->exists()) {
            ToastMagic::error('This domain is already in use.');
            return redirect()->back()->withInput();
        }

        try {
            // Create tenant with pending status
            $tenant = Tenant::create([
                'id' => $request->id,
                'data' => [
                    'name' => $request->name,
                    'description' => $request->description,
                    'created_by' => auth()->id(),
                ],
                'status' => Tenant::STATUS_PENDING,
            ]);

            // Create domain association
            $tenant->domains()->create([
                'domain' => $host,
            ]);

            // Log tenant creation
            $this->logTenantManagement('created', $tenant, [
                'domain' => $host,
                'description' => $request->description,
            ]);

            // 🚀 Dispatch background job with proper queue
            CreateTenantJob::dispatch($tenant)
                ->onQueue('tenant-creation')
                ->delay(now()->addSeconds(5)); // Small delay to ensure DB commit

            ToastMagic::success('Tenant creation started! Setup will complete in the background.');
            return redirect()->route('superadmin.tenants.index');

        } catch (\Throwable $e) {
            Log::error("Tenant creation failed", [
                'tenant_id' => $request->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            ToastMagic::error('Failed to create tenant. Please try again or contact support.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Toggle tenant status (activate/deactivate)
     */
    public function toggleStatus(Request $request, Tenant $tenant)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            if ($tenant->isActive()) {
                $tenant->deactivate($request->reason);
                $action = 'deactivated';
            } else {
                $tenant->activate();
                $action = 'activated';
            }

            $this->logTenantManagement($action, $tenant, [
                'reason' => $request->reason,
                'previous_status' => $tenant->getOriginal('status'),
                'new_status' => $tenant->status,
            ]);

            ToastMagic::success("Tenant has been {$action} successfully.");

        } catch (\Throwable $e) {
            Log::error("Failed to toggle tenant status", [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);

            ToastMagic::error('Failed to update tenant status. Please try again.');
        }

        return redirect()->back();
    }

    /**
     * Suspend a tenant
     */
    public function suspend(Request $request, Tenant $tenant)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            $tenant->suspend($request->reason);

            $this->logTenantManagement('suspended', $tenant, [
                'reason' => $request->reason,
                'previous_status' => $tenant->getOriginal('status'),
                'new_status' => $tenant->status,
            ]);

            ToastMagic::success('Tenant has been suspended.');

        } catch (\Throwable $e) {
            Log::error("Failed to suspend tenant", [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);

            ToastMagic::error('Failed to suspend tenant. Please try again.');
        }

        return redirect()->back();
    }

    /**
     * Show tenant details
     */
    public function show(Tenant $tenant)
    {
        $tenant->load('domains');

        // Get tenant statistics
        $stats = $this->getTenantStatistics($tenant);

        // Get recent activity logs
        $recentLogs = activity()
            ->where('properties->tenant_id', $tenant->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('super-admin.tenants.show', compact('tenant', 'stats', 'recentLogs'));
    }

    /**
     * Get tenant statistics
     */
    private function getTenantStatistics(Tenant $tenant): array
    {
        $stats = [
            'users' => 0,
            'applications' => 0,
            'seasons' => 0,
            'centers' => 0,
        ];

        try {
            if ($tenant->isActive()) {
                // Initialize tenant context to get statistics
                tenancy()->initialize($tenant);

                $stats['users'] = \App\Models\User::count();
                $stats['applications'] = \App\Models\LoanApplication::count() ?? 0;
                $stats['seasons'] = \App\Models\Season::count() ?? 0;
                $stats['centers'] = \App\Models\CollectionCenter::count() ?? 0;

                tenancy()->end();
            }
        } catch (\Exception $e) {
            Log::warning("Failed to get tenant statistics", [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
        }

        return $stats;
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
