<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonetaryReturn;

class MonetaryReturnController extends Controller
{
    public function index(Request $request)
    {
        $returns = MonetaryReturn::with(['application.farmer', 'application.commodity_allocations'])
            ->where('status', 'paid')
            ->latest()
            ->paginate(15);

        $totalCollected = MonetaryReturn::where('status', 'paid')->sum('amount');

        return view('admin.monetary-returns.index', compact('returns', 'totalCollected'));
    }
}
