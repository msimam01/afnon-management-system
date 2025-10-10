<?php

namespace App\Console\Commands;

use App\Models\Season;
use App\Models\SuperAdmin\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCloseExpiredSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seasons:auto-close-expired {tenant?} {--dry-run : Show what would be closed without actually closing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close seasons that have reached their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant');
        $isDryRun = $this->option('dry-run');

        if ($tenantId) {
            $tenants = [Tenant::findOrFail($tenantId)];
        } else {
            $tenants = Tenant::all();
        }

        $totalClosed = 0;
        $totalTenants = 0;

        foreach ($tenants as $tenant) {
            $totalTenants++;
            $this->info("Processing tenant: {$tenant->name} (ID: {$tenant->id})");

            // Switch to tenant context
            tenancy()->initialize($tenant);

            $this->info($isDryRun ? 'DRY RUN: Checking for expired seasons...' : 'Checking for expired seasons...');

            // Find seasons that are still open and their end_date has passed
            $expiredSeasons = Season::where('status', 'open')
                ->where('end_date', '<', now())
                ->get();

            if ($expiredSeasons->isEmpty()) {
                $this->info('No expired seasons found for this tenant.');
                continue;
            }

            $this->info("Found {$expiredSeasons->count()} expired season(s):");

            foreach ($expiredSeasons as $season) {
                $this->line("  - {$season->name} (ended: {$season->end_date->format('Y-m-d')})");
            }

            if ($isDryRun) {
                $this->warn('DRY RUN: No changes made for this tenant.');
                continue;
            }

            // Close the expired seasons
            $closedCount = 0;
            foreach ($expiredSeasons as $season) {
                $season->update(['status' => 'closed']);
                Log::info("Automatically closed expired season: {$season->name} (UUID: {$season->uuid}) for tenant {$tenant->name}");
                $closedCount++;
            }

            $totalClosed += $closedCount;
            $this->info("Successfully closed {$closedCount} season(s) for tenant {$tenant->name}.");
        }

        if ($isDryRun) {
            $this->warn('DRY RUN completed. Use without --dry-run to actually close seasons.');
        } else {
            $this->info("🎉 Auto-close command completed: {$totalClosed} seasons closed across {$totalTenants} tenant(s).");
            Log::info("Auto-close command completed: {$totalClosed} seasons closed across {$totalTenants} tenants");
        }

        return Command::SUCCESS;
    }
}
