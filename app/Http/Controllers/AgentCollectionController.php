<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class AgentCollectionController extends Controller
{
    public function verify(Request $request)
    {
        $agent = auth()->user();
        // $centerId = $agent->center_id;
        // $filter = $request->query('filter');
        // $season = $request->query('season');
    
        // $query = Application::with(['farmer', 'farm', 'season', 'commodity_allocations', 'applicationCenter'])
        //     ->whereHas('applicationCenter', function ($q) use ($centerId) {
        //         $q->where('collection_center_id', $centerId);
        //     });
    
        // if ($season) {
        //     $query->whereHas('season', function ($q) use ($season) {
        //         $q->where('name', 'like', str_contains($season, 'dry') ? '%Dry%' : '%Wet%');
        //     });
        // }
    
        // if ($filter) {
        //     $query->whereHas('farmer', function ($q) use ($filter) {
        //         $q->where('full_name', 'like', "%$filter%")
        //           ->orWhere('registration_number', 'like', "%$filter%");
        //     });
        // }
    
        $query = Application::with(['farmer', 'farm', 'season', 'commodity_allocations', 'applicationCenter']);

        // Check if AJAX request
        if ($request->ajax()) {
            return response()->json($query->get());
        }
    
        // Otherwise, return the Blade page
        return view('agent.verify-collection');
    }
    


    public function verifyCollection(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'idCard' => 'required|image|max:2048',
            'commodityPhoto' => 'required|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $application = Application::findOrFail($request->application_id);

        // Save uploaded images
        $idCardPath = $request->file('idCard')->store('collections/id_cards', 'public');
        $commodityPath = $request->file('commodityPhoto')->store('collections/commodities', 'public');

        // Here you can also create a CommodityReturn record
        $application->status = 'collected';
        $application->collected_by_agent_id = auth()->id(); // Track who verified
        $application->save();


        // Optional: save verification log / notes
        // CommodityReturn::create([...]);

        return response()->json(['message' => 'Collection verified successfully']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('agent.verify-collection'); // blade file path
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
