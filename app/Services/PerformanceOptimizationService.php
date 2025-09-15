<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceOptimizationService
{
    /**
     * Optimize database queries with proper caching
     */
    public static function getCachedData(string $key, callable $callback, int $ttl = 1800)
    {
        $tenantId = self::getTenantId();
        $cacheKey = "{$tenantId}_{$key}";

        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Get tenant ID for cache namespacing
     */
    private static function getTenantId(): string
    {
        try {
            return function_exists('tenant') && tenant() ? tenant('id') : 'central';
        } catch (\Throwable $e) {
            return 'central';
        }
    }

    /**
     * Optimize application statistics query
     */
    public static function getApplicationStats(?string $season = null): array
    {
        return self::getCachedData(
            'app_stats_' . ($season ?: 'all'),
            function () use ($season) {
                $query = DB::table('applications');

                if ($season) {
                    $query->join('seasons', 'applications.season_id', '=', 'seasons.id')
                          ->where('seasons.name', $season);
                }

                return [
                    'total_pending' => (clone $query)->where('status', 'pending')->count(),
                    'total_approved' => (clone $query)->where('status', 'approved')->count(),
                    'total_distributed' => (clone $query)->where('status', 'distributed')->count(),
                    'total_rejected' => (clone $query)->where('status', 'rejected')->count(),
                ];
            },
            300 // 5 minutes cache
        );
    }

    /**
     * Optimize seasons list query
     */
    public static function getSeasonsList(): \Illuminate\Database\Eloquent\Collection
    {
        return self::getCachedData(
            'seasons_list',
            function () {
                return \App\Models\Season::select('id', 'name', 'status')->get();
            },
            1800 // 30 minutes cache
        );
    }

    /**
     * Optimize centers list query
     */
    public static function getCentersList(): array
    {
        return self::getCachedData(
            'centers_list',
            function () {
                $collectionCenters = \App\Models\Center::whereIn('type', ['collection', 'both'])
                    ->select('id', 'name', 'type')
                    ->get();

                $returnCenters = \App\Models\Center::whereIn('type', ['return', 'both'])
                    ->select('id', 'name', 'type')
                    ->get();

                return [
                    'collection' => $collectionCenters,
                    'return' => $returnCenters
                ];
            },
            1800 // 30 minutes cache
        );
    }

    /**
     * Clear performance caches
     */
    public static function clearCaches(): void
    {
        $tenantId = self::getTenantId();
        $patterns = [
            "{$tenantId}_app_stats_*",
            "{$tenantId}_seasons_list",
            "{$tenantId}_centers_list",
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }

    /**
     * Optimize image loading
     */
    public static function optimizeImageLoading(string $imagePath): string
    {
        // Add lazy loading and optimization attributes
        return $imagePath . '?w=400&h=auto&q=80&f=webp';
    }

    /**
     * Get optimized pagination settings
     */
    public static function getPaginationSettings(): array
    {
        return [
            'per_page' => 20,
            'max_per_page' => 100,
            'default_sort' => 'created_at',
            'default_direction' => 'desc'
        ];
    }
}
