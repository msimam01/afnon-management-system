<?php

namespace App\Http\Controllers\Global;

use App\Models\GlobalCommodity;
use App\Models\GlobalCommodityCategory;
use App\Services\TenantSyncService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GlobalCommodityController extends Controller
{
    protected $syncService;

    public function __construct(TenantSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        
        $commodities = GlobalCommodity::with('category')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(15);

        $seasons = \App\Models\GlobalSeason::orderBy('name')->get();

        return view('global.commodities.index', compact('commodities', 'seasons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = GlobalCommodityCategory::orderBy('name')->get();
        return view('global.commodities.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:global_commodity_categories,id',
            'type' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'qtyPerHectare' => 'required|numeric|min:0.01',
        ]);
        
        try {
            DB::beginTransaction();
            
            $commodity = GlobalCommodity::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'unit' => $validated['unit'],
                'price_per_unit' => $validated['price'],
                'quantity_per_hectare' => $validated['qtyPerHectare'],
            ]);

            DB::commit();
            
            return redirect()->route('global.commodities.index')
                ->with('success', 'Commodity created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating commodity: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $commodity = GlobalCommodity::where('uuid', $uuid)->firstOrFail();
        $categories = GlobalCommodityCategory::orderBy('name')->get();
        
        // Check if commodity is allocated to any tenants
        $hasAllocations = $commodity->seasons()
            ->whereHas('tenantAllocations')
            ->exists();
        
        return view('global.commodities.edit', compact('commodity', 'categories', 'hasAllocations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $commodity = GlobalCommodity::where('uuid', $uuid)->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:global_commodity_categories,id',
            'type' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'required|numeric|min:0',
            'quantity_per_hectare' => 'required|numeric|min:0.01',
            'sync_to_tenants' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $commodity->update($validated);
            
            // If sync_to_tenants is true, sync the updates to all tenants
            if ($request->input('sync_to_tenants', false)) {
                $syncResults = $this->syncService->syncCommodityUpdate($commodity);
                
                $successCount = collect($syncResults)->where('success', true)->count();
                $totalCount = count($syncResults);
                
                DB::commit();
                
                $message = "Commodity updated successfully and synced to {$successCount} of {$totalCount} tenants.";
                return redirect()->route('global.commodities.index')
                    ->with('success', $message);
            }
            
            DB::commit();
            
            return redirect()->route('global.commodities.index')
                ->with('success', 'Commodity updated successfully. Remember to sync to tenants if needed.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating commodity: ' . $e->getMessage());
            return back()->with('error', 'Error updating commodity: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $commodity = GlobalCommodity::where('uuid', $uuid)->firstOrFail();
        
        try {
            // Prevent deletion if commodity is in use
            if ($commodity->seasons()->exists()) {
                return back()->with('error', 'Cannot delete commodity as it is associated with one or more seasons');
            }

            $commodity->delete();
            
            return redirect()->route('global.commodities.index')
                ->with('success', 'Commodity deleted successfully');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting commodity: ' . $e->getMessage());
        }
    }
}