<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\SuperAdmin\Tenant;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    /**
     * Display activity logs for super admin
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest();

        // Filter by tenant if specified
        if ($request->filled('tenant_id')) {
            $query->where('properties->tenant_id', $request->tenant_id);
        }

        // Filter by user if specified
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter by action type
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(50);

        // Get filter options
        $tenants = Tenant::select('id', 'data')->get()->map(function ($tenant) {
            return [
                'id' => $tenant->id,
                'name' => $tenant->name
            ];
        });

        $logTypes = Activity::distinct('log_name')->pluck('log_name');

        return view('super-admin.logs.index', compact('logs', 'tenants', 'logTypes'));
    }

    /**
     * Show detailed log entry
     */
    public function show($uuid)
    {
        try {
            // Find log by UUID
            $log = Activity::findByUuid($uuid);
            $log->load(['causer', 'subject']);
            return view('super-admin.logs.show', compact('log'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to show log details', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(404, 'Log entry not found or invalid UUID');
        }
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest();

        // Apply same filters as index
        if ($request->filled('tenant_id')) {
            $query->where('properties->tenant_id', $request->tenant_id);
        }

        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->limit(10000)->get(); // Limit for performance

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Date/Time',
                'User',
                'Action',
                'Description',
                'Tenant',
                'IP Address',
                'User Agent'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->causer ? $log->causer->name . ' (' . $log->causer->email . ')' : 'System',
                    $log->log_name,
                    $log->description,
                    $log->properties['tenant_id'] ?? 'Central',
                    $log->properties['ip_address'] ?? 'N/A',
                    $log->properties['user_agent'] ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get activity statistics
     */
    public function statistics()
    {
        $stats = [
            'total_activities' => Activity::count(),
            'today_activities' => Activity::whereDate('created_at', today())->count(),
            'this_week_activities' => Activity::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month_activities' => Activity::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Activity by type
        $activityByType = Activity::select('log_name', DB::raw('count(*) as count'))
            ->groupBy('log_name')
            ->orderBy('count', 'desc')
            ->get();

        // Activity by day (last 30 days)
        $activityByDay = Activity::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'stats' => $stats,
            'activity_by_type' => $activityByType,
            'activity_by_day' => $activityByDay
        ]);
    }
}
