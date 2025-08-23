<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CollectionVerification;
use App\Models\Season;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $seasonId = $request->query('season'); // UUID or ID of selected season

        $applicationsQuery = Application::query();
        $collectionsQuery  = CollectionVerification::query();

        if ($seasonId && $seasonId !== 'all') {
            $applicationsQuery->where('season_id', $seasonId);
            $collectionsQuery->whereHas('application', fn($q) => $q->where('season_id', $seasonId));
        }

        $totalApplications = Application::when($seasonId && $seasonId !== 'all', fn($q) => $q->where('season_id', $seasonId))->count();
        $totalApproved     = Application::when($seasonId && $seasonId !== 'all', fn($q) => $q->where('season_id', $seasonId))->where('status', 'approved')->count();
        $totalRejected     = Application::when($seasonId && $seasonId !== 'all', fn($q) => $q->where('season_id', $seasonId))->where('status', 'rejected')->count();

        $totalDistributed  = $collectionsQuery->count();
        $remaining         = max($totalApproved - $totalDistributed, 0);

        $chartData = [
            'labels' => ['Total', 'Approved', 'Rejected'],
            'values' => [$totalApplications, $totalApproved, $totalRejected],
        ];

        $seasons = Season::all();

        return view('admin.dashboard', compact(
            'totalApplications',
            'totalApproved',
            'totalRejected',
            'totalDistributed',
            'remaining',
            'chartData',
            'seasons',
            'seasonId'
        ));
    }
}
