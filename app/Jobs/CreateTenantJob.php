<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\SuperAdmin\Tenant;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tenant;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant)
    {
        //
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Optional: create default admin user inside tenant context
            tenancy()->initialize($this->tenant);
            Log::info("🔐 Initialized tenant context for {$this->tenant->id}");

            // Ensure role exists before assignment
            if (!Role::where('name', 'admin')->exists()) {
                Role::create(['name' => 'admin', 'tenant_id' => tenant('id')]);
                Log::info("🔐 Initialized tenant context for {$this->tenant->id}");
            }

            \App\Models\User::create([
                'name' => 'Default Admin',
                'email' => 'admin@' . $this->tenant->id . '.com',
                'password' => bcrypt('password'),
            ])->assignRole('admin');
            Log::info("✅ Created admin role");
            tenancy()->end();
            $this->tenant->update(['status' => 'ready']);
            Log::info("✅ Tenant {$this->tenant->id} setup complete.");
            
        } catch (\Throwable $e) {
            $this->tenant->update(['status' => 'failed']);
            Log::error("❌ Tenant {$this->tenant->id} creation failed: " . $e->getMessage());
        }
    }
}
