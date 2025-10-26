<?php

namespace App\Jobs;

use App\Models\GlobalSeason;
use App\Services\TenantSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncSeasonToTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $season;
    public $tenantId;
    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(GlobalSeason $season, string $tenantId)
    {
        $this->season = $season;
        $this->tenantId = $tenantId;
    }

    /**
     * Execute the job.
     */
    public function handle(TenantSyncService $syncService)
    {
        Log::info("Starting sync job for season {$this->season->id} to tenant {$this->tenantId}");

        try {
            $result = $syncService->syncSeasonToTenant($this->season, $this->tenantId);

            if ($result['success']) {
                Log::info("Successfully synced season {$this->season->id} to tenant {$this->tenantId}");
            } else {
                Log::warning("Failed to sync season {$this->season->id} to tenant {$this->tenantId}: {$result['message']}");
            }

            return $result;

        } catch (\Exception $e) {
            Log::error("Error in sync job for season {$this->season->id} to tenant {$this->tenantId}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error("Sync job failed for season {$this->season->id} to tenant {$this->tenantId}: " . $exception->getMessage());
        
        // You could send notification to admins here
    }
}