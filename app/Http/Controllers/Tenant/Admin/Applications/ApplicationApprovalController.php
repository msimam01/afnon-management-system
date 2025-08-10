<?php

namespace App\Http\Controllers\Tenant\Admin\Applications;

use App\Models\Center;
use App\Models\Application;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CommodityAllocation;
use App\Http\Controllers\Controller;

class ApplicationApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function approve(Request $request, Application $application)
    {
        $validated = $request->validate([
            'allocations' => 'required|array',
            'allocations.*.commodity_id' => 'required|exists:commodities,id',
            'allocations.*.center_id' => 'nullable|exists:centers,id',
            'allocations.*.allocated_quantity' => 'required|integer|min:1',
            'allocations.*.unit_price' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($application, $validated) {
            // 1. Update application status
            $application->update([
                'status' => 'approved',
            ]);

            // 2. Create allocations
            foreach ($validated['allocations'] as $alloc) {
                CommodityAllocation::create([
                    'uuid' => Str::uuid(),
                    'application_id' => $application->id,
                    'commodity_id' => $alloc['commodity_id'],
                    'center_id' => $alloc['center_id'] ?? null,
                    'allocated_quantity' => $alloc['allocated_quantity'],
                    'unit_price' => $alloc['unit_price'],
                    'value' => $alloc['unit_price'] ? $alloc['allocated_quantity'] * $alloc['unit_price'] : null,
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('applications.index')->with('success', 'Application approved and allocations created.');
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
