<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CollectionVerification;
use App\Models\ReturnVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $agent = optional(Auth::guard('tenant')->user())->agent;
        $today = Carbon::today();

        // If the user doesn't have an agent profile yet, avoid crashes and show zeros
        if (!$agent) {
            $totalFarmers = 0;
            $collectionsVerified = 0;
            $returnsVerified = 0;
            $todayCollections = 0;
            $todayReturns = 0;
            $todayTasks = 0;

            $chartData = [
                'labels' => ['Collections Verified', 'Returns Verified'],
                'values' => [0, 0],
            ];

            return view('agent.dashboard', compact(
                'totalFarmers',
                'collectionsVerified',
                'returnsVerified',
                'todayTasks',
                'chartData'
            ))->with('agentSetupRequired', true);
        }

        // Total farmers assigned to agent's center (if no center assigned yet, show 0)
        $totalFarmers = 0;
        if (!empty($agent->center_id)) {
            $totalFarmers = Application::whereHas('applicationCenter', function ($q) use ($agent) {
                $q->where('collection_center_id', $agent->center_id);
            })->count();
        }

        // Collections and returns counts (these are tied to agent id, safe if agent exists)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
