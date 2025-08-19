<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Models\SuperAdmin\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $tenants = Tenant::all();
        $totalTenants = $tenants->count();

        $totalFarmers = 0;
        $totalApplications = 0;
        $totalApproved = 0;
        $totalCollected = 0;
        $totalReturned = 0;
        $stateBreakdown = [];
        $tenantRows = [];

        // build per-tenant rows (loop tenants and switch to tenant DB)
        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);

            $farmers = (int) \App\Models\Farmer::count();
            $applications = (int) \App\Models\Application::count();
            $approved = (int) \App\Models\Application::where('status', 'approved')->count();
            $collected = (int) \App\Models\CollectionVerification::count();
            $returned = (int) \App\Models\ReturnVerification::count();

            $tenantRows[] = [
                'id' => $tenant->id,
                'name' => $tenant->name ?? $tenant->domain ?? "Tenant {$tenant->id}",
                'farmers' => $farmers,
                'applications' => $applications,
                'approved' => $approved,
                'collected' => $collected,
                'returned' => $returned,
            ];

            $totalFarmers += $farmers;
            $totalApplications += $applications;
            $totalApproved += $approved;
            $totalCollected += $collected;
            $totalReturned += $returned;

            // state breakdown (per tenant)
            $states = \App\Models\Farmer::select('state')
                ->selectRaw('COUNT(id) as total_farmers')
                ->groupBy('state')
                ->get();

            foreach ($states as $s) {
                $stateName = $s->state ?? 'Unknown';
                if (!isset($stateBreakdown[$stateName])) {
                    $stateBreakdown[$stateName] = [
                        'farmers' => 0,
                        'collected' => 0,
                        'returned' => 0,
                    ];
                }

                $stateBreakdown[$stateName]['farmers'] += (int) $s->total_farmers;

                $collectedCount = (int) \App\Models\CollectionVerification::whereHas('application.farmer', function ($q) use ($stateName) {
                    $q->where('state', $stateName);
                })->count();

                $returnedCount = (int) \App\Models\ReturnVerification::whereHas('application.farmer', function ($q) use ($stateName) {
                    $q->where('state', $stateName);
                })->count();

                $stateBreakdown[$stateName]['collected'] += $collectedCount;
                $stateBreakdown[$stateName]['returned'] += $returnedCount;
            }

            tenancy()->end();
        }

        // --- server-side search ---
        $q = $request->query('q', null);
        if ($q) {
            $tenantRows = array_values(array_filter($tenantRows, function ($row) use ($q) {
                return str_contains(strtolower($row['name']), strtolower($q)) || str_contains((string)$row['id'], (string)$q);
            }));
        }

        // --- CSV export (exports ALL rows, not just current page) ---
        if ($request->query('export') === 'csv') {
            $filename = 'tenants_export_' . date('Ymd_His') . '.csv';
            $columns = ['id', 'name', 'farmers', 'applications', 'approved', 'collected', 'returned'];

            $callback = function () use ($tenantRows, $columns) {
                $fh = fopen('php://output', 'w');
                // BOM for Excel (optional)
                // fprintf($fh, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($fh, $columns);
                foreach ($tenantRows as $row) {
                    fputcsv($fh, [
                        $row['id'],
                        $row['name'],
                        $row['farmers'],
                        $row['applications'],
                        $row['approved'],
                        $row['collected'],
                        $row['returned'],
                    ]);
                }
                fclose($fh);
            };

            return response()->streamDownload($callback, $filename, [
                'Content-Type' => 'text/csv',
            ]);
        }

        // --- server-side pagination ---
        $perPage = (int) $request->query('per_page', 10);
        $page = (int) $request->query('page', 1);
        $totalRows = count($tenantRows);

        // slice for current page
        $offset = ($page - 1) * $perPage;
        $pagedRows = array_slice($tenantRows, $offset, $perPage);

        $tenantPaginator = new LengthAwarePaginator(
            $pagedRows,
            $totalRows,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        // Prepare state chart arrays (top states)
        arsort($stateBreakdown);
        $stateBreakdown = array_slice($stateBreakdown, 0, 12, true);

        $tenantGrowthLabels = array_keys($stateBreakdown);
        $tenantGrowthData = array_map(fn($v) => $v['farmers'], array_values($stateBreakdown));
        if (empty($tenantGrowthLabels)) {
            $tenantGrowthLabels = ['No Data'];
            $tenantGrowthData = [0];
        }

        return view('super-admin.dashboard', [
            'totalTenants' => $totalTenants,
            'totalFarmers' => $totalFarmers,
            'totalApplications' => $totalApplications,
            'totalApproved' => $totalApproved,
            'totalCollected' => $totalCollected,
            'totalReturned' => $totalReturned,
            'tenantPaginator' => $tenantPaginator,
            'tenantGrowthLabels' => $tenantGrowthLabels,
            'tenantGrowthData' => $tenantGrowthData,
            'q' => $q,
            'perPage' => $perPage,
        ]);
    }
}
