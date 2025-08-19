<?php

namespace App\Http\Controllers\Tenant\Admin\Centers;

use App\Models\Center;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CollectionCenters extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Center::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        $centers = $query->paginate(15)->withQueryString();
        return view("admin.centers.index", compact('centers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.centers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenant = strtoupper(tenant()->id ?? 'None');
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'lga' => 'required|string',
            'address' => 'required|string',
        ]);
        $centers = Center::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'state' => $tenant,
            'lga' => $validated['lga'],
            'address' => $validated['address'],
        ]);
        if ($centers) {
            ToastMagic::success('Center created successfully');
            return redirect()->route('admin.centers.index');
        } else {
            ToastMagic::error('Fail to create center');
            return redirect()->back()->withInput(request()->all());
        }
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
    public function edit(string $uuid)
    {
        $center = Center::whereUuid($uuid)->first();
        return view("admin.centers.edit", compact('center'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        // return $request;
        $tenant = strtoupper(tenant()->id ?? 'None');
        $center = Center::whereUuid($uuid)->first();
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'lga' => 'required|string',
            'address' => 'required|string',
        ]);
        $update = $center->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'state' => $tenant,
            'lga' => $validated['lga'],
            'address' => $validated['address'],
        ]);
        if ($update) {
            ToastMagic::success('Center updated successfully');
            return redirect()->route('admin.centers.index');
        }
        ToastMagic::error('Fail to update center');
        return redirect()->back()->withInput(request()->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $delete = Center::whereUuid($uuid)->delete();
        if ($delete) {
            ToastMagic::success('Center deleted successfully');
            return redirect()->route('admin.centers.index');
        }
        ToastMagic::error('Fail to delete center');
        return redirect()->back()->withInput(request()->all());
    }
}
