<?php

namespace App\Services;

use App\Models\GlobalSeason;
use App\Models\GlobalTenantAllocation;
use App\Models\SuperAdmin\Tenant;
use App\Models\GlobalCommodity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class ReportService
{
    /**
     * Generate Season Allocation Report - Overview of all tenants' allocations
     */
    public function generateSeasonAllocationReport(string $seasonUuid): ?array
    {
        $cacheKey = "season_allocation_report_{$seasonUuid}";
        return Cache::remember($cacheKey, 1800, function () use ($seasonUuid) {
            try {
                $season = GlobalSeason::with(['commodities'])->where('uuid', $seasonUuid)->first();

                if (!$season) {
                    return null;
                }

                $seasonData = [
                    'season_name' => $season->name,
                    'season_type' => $season->type,
                    'loan_type' => $season->loan_type,
                    'budget' => $season->budget ?? 0,
                    'commodities' => [],
                    'tenants' => []
                ];

                // Process commodity data with allocation statistics
                foreach ($season->commodities as $commodity) {
                    $totalAllocatedToTenants = GlobalTenantAllocation::where('global_season_id', $season->id)
                        ->where('global_commodity_id', $commodity->id)
                        ->sum('allocated_stock');

                    $pivot = $season->commodities()->find($commodity->id)->pivot;
                    $totalStockInGlobal = $pivot ? $pivot->stock : 0;

                    $seasonData['commodities'][] = [
                        'id' => $commodity->id,
                        'name' => $commodity->name,
                        'category' => $commodity->category->name ?? 'Unknown',
                        'unit' => $commodity->unit,
                        'total_global_stock' => $totalStockInGlobal,
                        'total_stock' => $totalStockInGlobal,
                        'allocated_to_tenants' => $totalAllocatedToTenants,
                        'allocated' => $totalAllocatedToTenants,
                        'remaining_in_global' => max(0, $totalStockInGlobal - $totalAllocatedToTenants),
                        'percentage_allocated' => $totalStockInGlobal > 0
                            ? round(($totalAllocatedToTenants / $totalStockInGlobal) * 100, 2)
                            : 0
                    ];
                }

                // Process tenant allocations with distribution stats
                $allocations = GlobalTenantAllocation::with(['tenant', 'commodity'])
                    ->where('global_season_id', $season->id)
                    ->get()
                    ->groupBy('tenant_id');

                foreach ($allocations as $tenantId => $tenantAllocations) {
                    $tenant = $tenantAllocations->first()->tenant;

                    // Get distribution stats from tenant database
                    $distributionStats = $this->getTenantDistributionStats($tenant, $season);

                    // Get last sync time
                    $lastSync = DB::table('sync_logs')
                        ->where('tenant_id', $tenant->id)
                        ->where('season_id', $season->id)
                        ->where('status', 'success')
                        ->latest('created_at')
                        ->first();

                    $tenantData = [
                        'tenant_id' => $tenant->id,
                        'tenant_name' => $tenant->name,
                        'allocations' => $tenantAllocations->map(function($alloc) {
                            return [
                                'commodity' => $alloc->commodity->name,
                                'allocated_stock' => $alloc->allocated_stock
                            ];
                        }),
                        'distribution_stats' => $distributionStats,
                        'last_sync' => $lastSync ? $lastSync->created_at : null
                    ];

                    $seasonData['tenants'][] = $tenantData;
                }

                return $seasonData;

            } catch (\Exception $e) {
                Log::error("Failed to generate season allocation report: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Generate Tenant Distribution Report - Detailed farmer-level tracking
     */
    public function generateTenantDistributionReport(string $seasonUuid, string $tenantId): ?array
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->firstOrFail();
        $cacheKey = "tenant_distribution_report_{$seasonUuid}_{$tenantId}_{$season->loan_type}";

        return Cache::remember($cacheKey, 1800, function () use ($seasonUuid, $tenantId, $season) {
            try {
                $tenant = Tenant::findOrFail($tenantId);

                // Get central allocations
                $centralAllocations = GlobalTenantAllocation::with('commodity')
                    ->where('global_season_id', $season->id)
                    ->where('tenant_id', $tenant->id)
                    ->get()
                    ->keyBy('global_commodity_id');

                // Switch to tenant database
                $this->setTenantConnection($tenant);

                $tenantSeason = DB::connection('tenant')
                    ->table('seasons')
                    ->where('uuid', $seasonUuid)
                    ->first();

                if (!$tenantSeason) {
                    return null;
                }

                // Get tenant's current allocation status
                $tenantAllocations = DB::connection('tenant')
                    ->table('allocations')
                    ->join('commodities', 'allocations.commodity_id', '=', 'commodities.id')
                    ->where('allocations.season_id', $tenantSeason->id)
                    ->select(
                        'commodities.id as commodity_id',
                        'commodities.name as commodity_name',
                        'commodities.unit',
                        'allocations.allocated_stock as remaining_stock'
                    )
                    ->get()
                    ->keyBy('commodity_id');

                // Calculate distribution metrics per commodity
                $commodityDistribution = [];

                foreach ($centralAllocations as $globalCommodityId => $centralAlloc) {
                    $commodityName = $centralAlloc->commodity->name;
                    $originalAllocated = $centralAlloc->allocated_stock;

                    // Find matching tenant commodity
                    $tenantCommodity = DB::connection('tenant')
                        ->table('commodities')
                        ->where('name', $commodityName)
                        ->first();

                    if (!$tenantCommodity) {
                        continue;
                    }

                    // Get approved quantities from commodity_allocations
                    $approvedQuantity = DB::connection('tenant')
                        ->table('commodity_allocations')
                        ->join('applications', 'commodity_allocations.application_id', '=', 'applications.id')
                        ->where('applications.season_id', $tenantSeason->id)
                        ->where('commodity_allocations.commodity_name', $commodityName)
                        ->sum('commodity_allocations.allocated_quantity');

                    // Get collected quantities from collection_verifications
                    $collectedQuantity = DB::connection('tenant')
                        ->table('collection_verifications')
                        ->join('applications', 'collection_verifications.application_id', '=', 'applications.id')
                        ->where('applications.season_id', $tenantSeason->id)
                        ->where('collection_verifications.commodity_id', $tenantCommodity->id)
                        ->sum('collection_verifications.collected_quantity');

                    // Get remaining stock from allocations table
                    $remainingStock = $tenantAllocations->get($tenantCommodity->id)?->remaining_stock ?? 0;

                    // Calculate distributed (approved - remaining)
                    $distributedQuantity = max(0, $originalAllocated - $remainingStock);

                    $commodityData = [
                        'commodity_name' => $commodityName,
                        'unit' => $centralAlloc->commodity->unit,
                        'original_allocated' => $originalAllocated,
                        'approved_quantity' => $approvedQuantity,
                        'distributed_quantity' => $distributedQuantity,
                        'collected_quantity' => $collectedQuantity,
                        'remaining_stock' => $remainingStock,
                        'collection_variance' => $distributedQuantity - $collectedQuantity,
                    ];

                    // For complete-loan, add return metrics
                    if ($season->loan_type === 'complete-loan') {
                        $returnData = DB::connection('tenant')
                            ->table('return_verifications')
                            ->join('applications', 'return_verifications.application_id', '=', 'applications.id')
                            ->where('applications.season_id', $tenantSeason->id)
                            ->selectRaw('
                                SUM(expected_quantity) as total_expected,
                                SUM(returned_quantity) as total_returned,
                                SUM(variance) as total_variance,
                                COUNT(CASE WHEN partial_return = 1 THEN 1 END) as partial_returns_count
                            ')
                            ->first();

                        $commodityData['return_metrics'] = [
                            'expected_return' => $returnData->total_expected ?? 0,
                            'actual_returned' => $returnData->total_returned ?? 0,
                            'return_variance' => $returnData->total_variance ?? 0,
                            'partial_returns' => $returnData->partial_returns_count ?? 0,
                        ];
                    }

                    $commodityDistribution[] = $commodityData;
                }

                // Get farmer-level distribution details
                $farmerDistributions = $this->getFarmerDistributions($tenantSeason->id, $season->loan_type);

                return [
                    'tenant_id' => $tenant->id,
                    'tenant_name' => $tenant->id,
                    'season_name' => $season->name,
                    'loan_type' => $season->loan_type,
                    'central_allocations' => $centralAllocations->map(fn($a) => [
                        'commodity' => $a->commodity->name,
                        'allocated_stock' => $a->allocated_stock,
                    ])->values(),
                    'commodity_distribution' => $commodityDistribution,
                    'farmer_distributions' => $farmerDistributions,
                    'summary' => [
                        'total_farmers' => count($farmerDistributions),
                        'farmers_collected' => collect($farmerDistributions)->filter(fn($f) => $f['total_collected'] > 0)->count(),
                        'farmers_returned' => $season->loan_type === 'complete-loan'
                            ? collect($farmerDistributions)->filter(fn($f) => ($f['total_returned'] ?? 0) > 0)->count()
                            : null,
                    ]
                ];

            } catch (\Exception $e) {
                Log::error("Failed to generate tenant distribution report: " . $e->getMessage());
                Log::error($e->getTraceAsString());
                return null;
            }
        });
    }

    /**
     * Generate Return Compliance Report (Complete-Loan seasons only)
     */
    public function generateReturnComplianceReport(string $seasonUuid): ?array
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->first();

        if (!$season || $season->loan_type !== 'complete-loan') {
            return [];
        }

        $cacheKey = "return_compliance_report_{$seasonUuid}";
        return Cache::remember($cacheKey, 1800, function () use ($seasonUuid, $season) {
            try {
                $reportData = [];

                $tenantIds = GlobalTenantAllocation::where('global_season_id', $season->id)
                    ->distinct()
                    ->pluck('tenant_id');

                foreach ($tenantIds as $tenantId) {
                    $tenant = Tenant::find($tenantId);
                    if (!$tenant) continue;

                    $this->setTenantConnection($tenant);

                    $tenantSeason = DB::connection('tenant')
                        ->table('seasons')
                        ->where('uuid', $seasonUuid)
                        ->first();

                    if (!$tenantSeason) continue;

                    // Get return compliance statistics
                    $returnStats = DB::connection('tenant')
                        ->table('return_verifications')
                        ->join('applications', 'return_verifications.application_id', '=', 'applications.id')
                        ->where('applications.season_id', $tenantSeason->id)
                        ->selectRaw('
                            SUM(expected_quantity) as total_expected,
                            SUM(returned_quantity) as total_returned,
                            SUM(variance) as total_variance,
                            COUNT(DISTINCT application_id) as total_returns,
                            COUNT(CASE WHEN partial_return = 1 THEN 1 END) as partial_returns
                        ')
                        ->first();

                    // Get applications without returns (overdue)
                    $overdueApplications = DB::connection('tenant')
                        ->table('applications')
                        ->leftJoin('return_verifications', 'applications.id', '=', 'return_verifications.application_id')
                        ->where('applications.season_id', $tenantSeason->id)
                        ->where('applications.status', 'approved')
                        ->whereNull('return_verifications.id')
                        ->count();

                    // Get shortfall reasons summary
                    $shortfallReasons = DB::connection('tenant')
                        ->table('return_verifications')
                        ->join('applications', 'return_verifications.application_id', '=', 'applications.id')
                        ->where('applications.season_id', $tenantSeason->id)
                        ->whereNotNull('shortfall_reason')
                        ->where('shortfall_reason', '!=', '')
                        ->selectRaw('shortfall_reason, COUNT(*) as count')
                        ->groupBy('shortfall_reason')
                        ->get()
                        ->pluck('count', 'shortfall_reason')
                        ->toArray();

                    $reportData[] = [
                        'tenant_name' => $tenant->id,
                        'total_expected_returns' => $returnStats->total_expected ?? 0,
                        'total_returned' => $returnStats->total_returned ?? 0,
                        'variance' => $returnStats->total_variance ?? 0,
                        'total_return_records' => $returnStats->total_returns ?? 0,
                        'partial_returns' => $returnStats->partial_returns ?? 0,
                        'overdue_applications' => $overdueApplications,
                        'shortfall_reasons' => $shortfallReasons,
                        'compliance_rate' => $returnStats->total_expected > 0
                            ? round(($returnStats->total_returned / $returnStats->total_expected) * 100, 2)
                            : 0
                    ];
                }

                return $reportData;

            } catch (\Exception $e) {
                Log::error("Failed to generate return compliance report: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get tenant distribution statistics (called from central)
     */
    private function getTenantDistributionStats(Tenant $tenant, GlobalSeason $season): array
    {
        try {
            $this->setTenantConnection($tenant);

            $tenantSeason = DB::connection('tenant')
                ->table('seasons')
                ->where('uuid', $season->uuid)
                ->first();

            if (!$tenantSeason) {
                return [
                    'total_applications' => 0,
                    'approved_applications' => 0,
                    'collected_applications' => 0,
                    'returned_applications' => 0,
                ];
            }

            $stats = [
                'total_applications' => DB::connection('tenant')
                    ->table('applications')
                    ->where('season_id', $tenantSeason->id)
                    ->count(),

                'approved_applications' => DB::connection('tenant')
                    ->table('applications')
                    ->where('season_id', $tenantSeason->id)
                    ->where('status', 'approved')
                    ->count(),

                'collected_applications' => DB::connection('tenant')
                    ->table('collection_verifications')
                    ->join('applications', 'collection_verifications.application_id', '=', 'applications.id')
                    ->where('applications.season_id', $tenantSeason->id)
                    ->distinct('collection_verifications.application_id')
                    ->count(),
            ];

            if ($season->loan_type === 'complete-loan') {
                $stats['returned_applications'] = DB::connection('tenant')
                    ->table('return_verifications')
                    ->join('applications', 'return_verifications.application_id', '=', 'applications.id')
                    ->where('applications.season_id', $tenantSeason->id)
                    ->distinct('return_verifications.application_id')
                    ->count();
            }

            return $stats;

        } catch (\Exception $e) {
            Log::error("Failed to get tenant distribution stats: " . $e->getMessage());
            return [];
        }
    }

    /**
 * Get farmer-level distribution details
 */
private function getFarmerDistributions(int $tenantSeasonId, string $loanType): array
{
    try {
        $farmers = DB::connection('tenant')
            ->table('applications')
            ->join('farmers', 'applications.farmer_id', '=', 'farmers.id')
            ->where('applications.season_id', $tenantSeasonId)
            ->where('applications.status', 'approved')
            ->select(
                'farmers.id as farmer_id',
                'farmers.full_name as farmer_name',
                'farmers.registration_number',
                'applications.id as application_id',
                'applications.payment_status'
            )
            ->get();

        return $farmers->map(function ($farmer) use ($loanType) {
            // Get allocated commodities
            $allocations = DB::connection('tenant')
                ->table('commodity_allocations')
                ->where('application_id', $farmer->application_id)
                ->select('commodity_name', 'allocated_quantity', 'unit_price')
                ->get();

            // Get collected quantities
            $collections = DB::connection('tenant')
                ->table('collection_verifications')
                ->join('commodities', 'collection_verifications.commodity_id', '=', 'commodities.id')
                ->where('collection_verifications.application_id', $farmer->application_id)
                ->select('commodities.name as commodity_name', 'collection_verifications.collected_quantity')
                ->get()
                ->keyBy('commodity_name');

            // Get returned quantities for complete-loan
            $returns = collect([]);
            // Note: return_verifications does not store per-commodity data, only aggregates

            $commodities = $allocations->map(function ($alloc) use ($collections, $returns, $loanType) {
                $collected = $collections->get($alloc->commodity_name)?->collected_quantity ?? 0;

                $commodity = [
                    'name' => $alloc->commodity_name,
                    'allocated' => $alloc->allocated_quantity,
                    'collected' => $collected,
                    'unit_price' => $alloc->unit_price,
                ];

                if ($loanType === 'complete-loan') {
                    $commodity['returned'] = $returns->get($alloc->commodity_name)?->returned_quantity ?? 0;
                }

                return $commodity;
            });

            $result = [
                'farmer_id' => $farmer->farmer_id,
                'farmer_name' => $farmer->farmer_name,
                'registration_number' => $farmer->registration_number,
                'payment_status' => $farmer->payment_status ?? 'pending',
                'commodities' => $commodities,
                'total_allocated' => $commodities->sum('allocated'),
                'total_collected' => $commodities->sum('collected'),
            ];

            // Add return data for complete-loan
            if ($loanType === 'complete-loan') {
                $returnStats = DB::connection('tenant')
                    ->table('return_verifications')
                    ->where('application_id', $farmer->application_id)
                    ->selectRaw('
                        SUM(expected_quantity) as expected,
                        SUM(returned_quantity) as returned,
                        SUM(variance) as variance
                    ')
                    ->first();

                $returnDetails = DB::connection('tenant')
                    ->table('return_verifications')
                    ->where('application_id', $farmer->application_id)
                    ->select('expected_quantity', 'returned_quantity', 'variance', 'shortfall_reason', 'partial_return')
                    ->first();

                $result['total_returned'] = $returnStats->returned ?? 0;
                $result['expected_return'] = $returnStats->expected ?? 0;
                $result['return_variance'] = $returnStats->variance ?? 0;
                $result['return_shortfall_reason'] = $returnDetails->shortfall_reason ?? null;
                $result['return_partial_return'] = $returnDetails->partial_return ?? false;
            }

            return $result;
        })->toArray();

    } catch (\Exception $e) {
        Log::error("Failed to get farmer distributions: " . $e->getMessage());
        return [];
    }
}

    /**
     * Set up tenant database connection
     */
    protected function setTenantConnection(Tenant $tenant): void
    {
        $databaseName = $tenant->database()->getName();

        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $databaseName,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }
}
