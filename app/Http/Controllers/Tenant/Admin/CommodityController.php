<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Commodity::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('category', 'like', '%'.$request->search.'%');
        }

        $commodities = $query->latest()->paginate(15);
        $seasons = []; // load actual seasons if needed for filtering

        return view('admin.commodities.index', compact('commodities', 'seasons'));
    }

    public function create()
    {
        return view('admin.commodities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'unit' => 'required|string',
            'price' => 'required|numeric|min:0',
            'qtyPerHectare' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Commodity::create([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'price_per_unit' => $request->price,
            'quantity_per_hectare' => $request->qtyPerHectare,
            'stock' => $request->stock,
        ]);
        ToastMagic::success('Commodity created successfully.');
        return redirect()->route('admin.commodities.index');
    }

    public function edit(String $uuid)
    {
        $commodity = Commodity::whereUuid($uuid)->first();
        return view('admin.commodities.edit', compact('commodity'));
    }

    public function update(Request $request, String $uuid)
    {
        $commodity = Commodity::whereUuid($uuid)->first();
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'category' => 'required|string',
        //     'unit' => 'required|string',
        //     'price_per_unit	' => 'required|numeric|min:0',
        //     'quantity_per_hectare' => 'required|numeric|min:0',
        //     'stock' => 'required|integer|min:0',
        // ]);

        $commodity->update([
            'name' => $request->name,
            'category' => $request->category,
            'unit' => $request->unit,
            'price_per_unit' => $request->price_per_unit,
            'quantity_per_hectare' => $request->quantity_per_hectare,
            'stock' => $request->stock,
        ]);
        ToastMagic::success('Commodity updated successfully.');
        return redirect()->route('admin.commodities.index')
                         ->with('success', 'Commodity updated successfully.');
    }

    public function destroy(Commodity $commodity)
    {
        $commodity->delete();
        ToastMagic::success('Commodity deleted successfully.');
        return redirect()->route('admin.commodities.index');
    }
}
