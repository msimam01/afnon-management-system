<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonetaryReturn;
use App\Models\Season;

class MonetaryReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = MonetaryReturn::with(['application.farmer', 'application.commodity_allocations'])
            ->where('status', 'paid');

        // Search filter
        if ($request->filled('filter')) {
            $filter = $request->filter;
            $query->whereHas('application.farmer', function ($q) use ($filter) {
                $q->where('full_name', 'like', "%$filter%")
                  ->orWhere('registration_number', 'like', "%$filter%");
            });
        }

        // Season filter
        if ($request->filled('season')) {
            $query->whereHas('application.season', function ($q) use ($request) {
                $q->where('slug', $request->season);
            });
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . " 00:00:00",
                $request->to . " 23:59:59"
            ]);
        }

        $returns = $query->latest()->paginate(15);

        $totalCollected = $query->sum('amount');

        $seasons = Season::all();

        return view('admin.monetary-returns.index', compact('returns', 'totalCollected', 'seasons'));
    }
}
