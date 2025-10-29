<?php

namespace App\Jobs;

use App\Models\GlobalCommodity;
use App\Services\TenantSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncCommodityUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $commodity;
    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(GlobalCommodity $commodity)
    {
        $this->commodity = $commodity;
    }

    /**
     * Execute the job.
     */
    public function handle(TenantSyncService $syncService)
    {
        Log::info("Starting commodity update sync job for commodity {$this->commodity->id} - {$this->commodity->name}");

        try {
            $results = $syncService->syncCommodityUpdate($this->commodity);
            $successCount = collect($results)->where('success', true)->count();
            $totalCount = count($results);

            Log::info("Commodity update job completed for {$this->commodity->name}: {$successCount} of {$totalCount} tenants successful");

            return $results;

        } catch (\Exception $e) {
            Log::error("Error in commodity update sync job for {$this->commodity->name}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error("Commodity update sync job failed for {$this->commodity->name}: " . $exception->getMessage());
    }
}
