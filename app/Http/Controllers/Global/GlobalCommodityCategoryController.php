<?php

namespace App\Http\Controllers\Global;

use App\Models\GlobalCommodityCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class GlobalCommodityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = GlobalCommodityCategory::latest()->paginate(15);
        return view('global.commodity-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('global.commodity-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:global_commodity_categories,name',
            'description' => 'nullable|string',
        ]);

        $validated['name'] = strtolower($validated['name']);

        GlobalCommodityCategory::create($validated);

        return redirect()->route('global.commodity-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GlobalCommodityCategory $category)
    {
        return view('global.commodity-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GlobalCommodityCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:global_commodity_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $validated['name'] = strtolower($validated['name']);

        $category->update($validated);

        return redirect()->route('global.commodity-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GlobalCommodityCategory $category): JsonResponse
    {
        // Prevent deletion if category is in use
        if ($category->commodities()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category as it is associated with one or more commodities',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
