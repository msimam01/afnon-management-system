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

class SyncSeasonUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $season;
    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(GlobalSeason $season)
    {
        $this->season = $season;
    }

    /**
     * Execute the job.
     */
    public function handle(TenantSyncService $syncService)
    {
        Log::info("Starting season update sync job for season {$this->season->id} - {$this->season->name}");

        try {
            $results = $syncService->syncSeasonUpdate($this->season);
            $successCount = collect($results)->where('success', true)->count();
            $totalCount = count($results);

            Log::info("Season update job completed for {$this->season->name}: {$successCount} of {$totalCount} tenants successful");

            return $results;

        } catch (\Exception $e) {
            Log::error("Error in season update sync job for {$this->season->name}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error("Season update sync job failed for {$this->season->name}: " . $exception->getMessage());
    }
}
