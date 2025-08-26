<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class ApplicationCacheService
{
    const CACHE_TTL = 3600; // 1 hour
    const CACHE_PREFIX = 'app_';

    private static function prefix(): string
    {
        try {
            $tid = function_exists('tenant') && tenant() ? tenant('id') : 'central';
        } catch (\Throwable $e) {
            $tid = 'central';
        }
        return self::CACHE_PREFIX . $tid . '_';
    }

    /**
     * Get application with caching
     */
    public static function getByUuid(string $uuid, array $relations = [])
    {
        $cacheKey = self::prefix() . "uuid_{$uuid}";

        try {
            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($uuid, $relations) {
                $query = Application::whereUuid($uuid);

                if (!empty($relations)) {
                    $query->with($relations);
                }

                return $query->first();
            });
        } catch (\Exception $e) {
            // Fallback to direct database query if caching fails
            $query = Application::whereUuid($uuid);

            if (!empty($relations)) {
                $query->with($relations);
            }

            return $query->first();
        }
    }

    /**
     * Get application by reference with caching
     */
    public static function getByReference(string $reference, array $relations = [])
    {
        $cacheKey = self::prefix() . "ref_{$reference}";

        try {
            return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($reference, $relations) {
                $query = Application::where('reference_number', $reference);

                if (!empty($relations)) {
                    $query->with($relations);
                }

                return $query->first();
            });
        } catch (\Exception $e) {
            // Fallback to direct database query if caching fails
            $query = Application::where('reference_number', $reference);

            if (!empty($relations)) {
                $query->with($relations);
            }

            return $query->first();
        }
    }

    /**
     * Cache application data after creation/update
     */
    public static function cacheApplication(Application $application)
    {
        try {
            $application->load(['farmer', 'farm', 'season', 'commodities', 'commodity_allocations']);

            // Cache by UUID
            $uuidKey = self::prefix() . "uuid_{$application->uuid}";
            Cache::put($uuidKey, $application, self::CACHE_TTL);

            // Cache by reference
            $refKey = self::prefix() . "ref_{$application->reference_number}";
            Cache::put($refKey, $application, self::CACHE_TTL);
        } catch (\Exception $e) {
            // Silently fail if caching doesn't work
            \Log::warning('Failed to cache application: ' . $e->getMessage());
        }

        return $application;
    }

    /**
     * Clear application cache
     */
    public static function clearCache(Application $application)
    {
        try {
            $uuidKey = self::prefix() . "uuid_{$application->uuid}";
            $refKey = self::prefix() . "ref_{$application->reference_number}";

            Cache::forget($uuidKey);
            Cache::forget($refKey);
        } catch (\Exception $e) {
            // Silently fail if cache clearing doesn't work
            \Log::warning('Failed to clear application cache: ' . $e->getMessage());
        }
    }

    /**
     * Get paginated applications with caching
     */
    public static function getPaginatedApplications(array $filters = [], int $perPage = 15)
    {
        $cacheKey = self::prefix() . 'paginated_' . md5(serialize($filters) . $perPage);

        try {
            return Cache::remember($cacheKey, 300, function () use ($filters, $perPage) { // 5 min cache
                $query = Application::with(['farmer:id,full_name,registration_number,phone,bvn,nin,address,cluster',
                                          'season:id,name',
                                          'farm:id,size,location',
                                          'commodities:id,name,unit']);

                // Apply filters
                if (isset($filters['season'])) {
                    $query->whereHas('season', function ($q) use ($filters) {
                        $q->where('name', $filters['season']);
                    });
                }

                if (isset($filters['status'])) {
                    $query->where('status', $filters['status']);
                }

                if (isset($filters['search'])) {
                    $search = $filters['search'];
                    $query->whereHas('farmer', function ($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%")
                          ->orWhere('bvn', 'like', "%{$search}%");
                    });
                }

                return $query->latest()->paginate($perPage);
            });
        } catch (\Exception $e) {
            // Fallback to direct database query if caching fails
            $query = Application::with(['farmer:id,full_name,registration_number,phone,bvn,nin,address,cluster',
                                      'season:id,name',
                                      'farm:id,size,location',
                                      'commodities:id,name,unit']);

            // Apply filters
            if (isset($filters['season'])) {
                $query->whereHas('season', function ($q) use ($filters) {
                    $q->where('name', $filters['season']);
                });
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['search'])) {
                $search = $filters['search'];
                $query->whereHas('farmer', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('bvn', 'like', "%{$search}%");
                });
            }

            return $query->latest()->paginate($perPage);
        }
    }

    /**
     * Warm up cache for frequently accessed applications
     */
    public static function warmUpCache()
    {
        // Cache recent applications
        $recentApps = Application::with(['farmer', 'farm', 'season', 'commodities'])
                                ->latest()
                                ->limit(100)
                                ->get();

        foreach ($recentApps as $app) {
            self::cacheApplication($app);
        }

        return $recentApps->count();
    }

    /**
     * Get application statistics with caching
     */
    public static function getStatistics()
    {
        try {
            return Cache::remember(self::prefix() . 'stats', 1800, function () { // 30 min cache
                return [
                    'total' => Application::count(),
                    'pending' => Application::where('status', 'pending')->count(),
                    'approved' => Application::where('status', 'approved')->count(),
                    'rejected' => Application::where('status', 'rejected')->count(),
                    'today' => Application::whereDate('created_at', today())->count(),
                    'this_week' => Application::whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ])->count(),
                ];
            });
        } catch (\Exception $e) {
            // Fallback to direct database queries if caching fails
            return [
                'total' => Application::count(),
                'pending' => Application::where('status', 'pending')->count(),
                'approved' => Application::where('status', 'approved')->count(),
                'rejected' => Application::where('status', 'rejected')->count(),
                'today' => Application::whereDate('created_at', today())->count(),
                'this_week' => Application::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            ];
        }
    }

    /**
     * Bulk cache applications
     */
    public static function bulkCache(array $applications)
    {
        foreach ($applications as $app) {
            if ($app instanceof Application) {
                self::cacheApplication($app);
            }
        }
    }

    /**
     * Clear all application caches
     */
    public static function clearAllCache()
    {
        try {
            // Simple cache flush - use with caution as it clears all cache
            Cache::flush();
        } catch (\Exception $e) {
            // Silently fail if cache clearing doesn't work
            \Log::warning('Failed to clear all application cache: ' . $e->getMessage());
        }
    }
}
