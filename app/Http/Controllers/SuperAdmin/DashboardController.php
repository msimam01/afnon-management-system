<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Models\SuperAdmin\Tenant;
use App\Http\Controllers\Controller;

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
        $stateBreakdown = []; // ['State' => ['farmers'=>int, 'collected'=>int, 'returned'=>int]]

        $tenantRows = []; // per-tenant table rows

        foreach ($tenants as $tenant) {
            // initialize tenant DB context
            tenancy()->initialize($tenant);

            // gather tenant counts (these models live inside tenant DB)
            $farmers = (int) \App\Models\Farmer::count();
            $applications = (int) \App\Models\Application::count();
            $approved = (int) \App\Models\Application::where('status', 'approved')->count();
            $collected = (int) \App\Models\CollectionVerification::count();
            $returned = (int) \App\Models\ReturnVerification::count();

            // append per-tenant row
            $tenantRows[] = [
                'id' => $tenant->id,
                'name' => $tenant->name ?? $tenant->domain ?? "Tenant {$tenant->id}",
                'farmers' => $farmers,
                'applications' => $applications,
                'approved' => $approved,
                'collected' => $collected,
                'returned' => $returned,
            ];

            // add to totals
            $totalFarmers += $farmers;
            $totalApplications += $applications;
            $totalApproved += $approved;
            $totalCollected += $collected;
            $totalReturned += $returned;

            // state breakdown per tenant
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

                // count collected/returned for this state inside the tenant
                $collectedCount = (int) \App\Models\CollectionVerification::whereHas('application.farmer', function ($q) use ($stateName) {
                    $q->where('state', $stateName);
                })->count();

                $returnedCount = (int) \App\Models\ReturnVerification::whereHas('application.farmer', function ($q) use ($stateName) {
                    $q->where('state', $stateName);
                })->count();

                $stateBreakdown[$stateName]['collected'] += $collectedCount;
                $stateBreakdown[$stateName]['returned'] += $returnedCount;
            }

            // end tenant DB context (important)
            tenancy()->end();
        }

        // Prepare chart arrays (top states)
        arsort($stateBreakdown); // sort by farmers descending
        $stateBreakdown = array_slice($stateBreakdown, 0, 12, true); // top 12 states

        $tenantGrowthLabels = array_keys($stateBreakdown);
        $tenantGrowthData = array_map(fn($v) => $v['farmers'], array_values($stateBreakdown));

        if (empty($tenantGrowthLabels)) {
            $tenantGrowthLabels = ['No Data'];
            $tenantGrowthData = [0];
        }

        return view('super-admin.dashboard', [
            'totalTenants'       => $totalTenants,
            'totalFarmers'       => $totalFarmers,
            'totalApplications'  => $totalApplications,
            'totalApproved'      => $totalApproved,
            'totalCollected'     => $totalCollected,
            'totalReturned'      => $totalReturned,
            'tenantRows'         => $tenantRows,
            'tenantGrowthLabels' => $tenantGrowthLabels,
            'tenantGrowthData'   => $tenantGrowthData,
        ]);
    }
}
