<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CollectionVerification;
use App\Models\ReturnVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $agent = Auth::guard('tenant')->user()->agent;
        $today = Carbon::today();

        // Total farmers assigned to agent's center
        $totalFarmers = Application::whereHas('applicationCenter', fn($q) => $q->where('collection_center_id', $agent->center_id))
            ->count();

        // Collections and returns counts
        $collectionsVerified = CollectionVerification::where('agent_id', $agent->id)->count();
        $returnsVerified = ReturnVerification::where('agent_id', $agent->id)->count();

        // Total tasks done today (collections + returns)
        $todayCollections = CollectionVerification::where('agent_id', $agent->id)
            ->whereDate('created_at', $today)
            ->count();
        $todayReturns = ReturnVerification::where('agent_id', $agent->id)
            ->whereDate('created_at', $today)
            ->count();
        $todayTasks = $todayCollections + $todayReturns;

        // Chart data: collections vs returns
        $chartData = [
            'labels' => ['Collections Verified', 'Returns Verified'],
            'values' => [$collectionsVerified, $returnsVerified],
        ];

        return view('agent.dashboard', compact(
            'totalFarmers',
            'collectionsVerified',
            'returnsVerified',
            'todayTasks',
            'chartData'
        ));
    }
}
