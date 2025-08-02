<?php

namespace App\Http\Controllers;

use App\Models\SyncLog;
use Illuminate\Http\Request;
use App\Models\SuperAdmin\Tenant;
use App\Models\Central\CentralSeason;
use App\Models\Central\CentralCommodity;

class SyncLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SyncLog::query()->latest();
    
        if ($request->filled('tenant')) {
            $query->where('tenant_id', $request->tenant);
        }
    
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
    
        $logs = $query->get();
    
        // Map item name manually since we don’t have a morph relationship
        $logs->map(function ($log) {
            if ($log->type === 'season') {
                $log->item_name = optional(CentralSeason::find($log->item_id))->name ?? 'N/A';
            } elseif ($log->type === 'commodity') {
                $log->item_name = optional(CentralCommodity::find($log->item_id))->name ?? 'N/A';
            } else {
                $log->item_name = 'Unknown';
            }
            return $log;
        });
    
        // If searching by item name, filter after adding names
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $logs = $logs->filter(function ($log) use ($search) {
                return str_contains(strtolower($log->item_name), $search);
            })->values();
        }
    
        // Manual pagination for filtered results
        $perPage = 20;
        $page = request()->get('page', 1);
        $logsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $logs->forPage($page, $perPage),
            $logs->count(),
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => request()->query()]
        );
    
        $tenants = Tenant::all();
    
        return view('super-admin.sync-logs.index', [
            'logs' => $logsPaginated,
            'tenants' => $tenants
        ]);
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
