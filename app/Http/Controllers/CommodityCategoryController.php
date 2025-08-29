<?php

namespace App\Http\Controllers;

use App\Models\CommodityCategory;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

class CommodityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $data = $request->all();
        $data['name'] = strtolower($data['name']);
        CommodityCategory::create($data);
        ToastMagic::success('Commodity category created successfully.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(CommodityCategory $commodityCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommodityCategory $commodityCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommodityCategory $commodityCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommodityCategory $commodityCategory)
    {
        //
    }
}
