<?php

namespace App\Http\Controllers\Admin;

use App\Models\Season;
use Illuminate\Http\Request;
use App\Models\MonetaryReturn;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MonetaryReturnsExport;

class MonetaryReturnVerificationController extends Controller
{
     public function index(Request $request)
    {
        $query = MonetaryReturn::with(['application.farmer', 'application.commodity_allocations', 'application.season'])
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

    public function show($uuid)
    {
        $return = MonetaryReturn::where('uuid', $uuid)->firstOrFail();
        $return->load(['application.farmer', 'application.commodity_allocations', 'application.season']);

        return view('admin.monetary-returns.show', compact('return'));
    }

    public function reports(Request $request)
    {
        $query = MonetaryReturn::with(['application.farmer', 'application.commodity_allocations', 'application.season']);

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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . " 00:00:00",
                $request->to . " 23:59:59"
            ]);
        }

        $returns = $query->latest()->paginate(20);

        // Calculate statistics on the same filtered query
        $statistics = [
            'total_collected' => (clone $query)->where('status', 'paid')->sum('amount') ?? 0,
            'total_payments' => (clone $query)->where('status', 'paid')->count(),
            'pending_payments' => (clone $query)->where('status', 'pending')->count(),
            'average_payment' => (clone $query)->where('status', 'paid')->avg('amount') ?? 0,
        ];

        $seasons = Season::all();

        return view('admin.reports.monetary-returns', compact('returns', 'statistics', 'seasons'));
    }

    public function generateReport($uuid)
    {
        $return = MonetaryReturn::where('uuid', $uuid)->firstOrFail();
        $return->load(['application.farmer', 'application.commodity_allocations', 'application.season']);

        $pdf = Pdf::loadView('admin.reports.monetary-return-pdf', compact('return'));

        return $pdf->download('monetary-return-' . $return->tx_ref . '.pdf');
    }

    public function export($uuid)
    {
        $return = MonetaryReturn::where('uuid', $uuid)->firstOrFail();
        $return->load(['application.farmer', 'application.commodity_allocations', 'application.season']);

        return Excel::download(new MonetaryReturnsExport($return), 'monetary-return-' . $return->tx_ref . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = MonetaryReturn::with(['application.farmer', 'application.commodity_allocations', 'application.season']);

        // Apply same filters as reports
        if ($request->filled('filter')) {
            $filter = $request->filter;
            $query->whereHas('application.farmer', function ($q) use ($filter) {
                $q->where('full_name', 'like', "%$filter%")
                  ->orWhere('registration_number', 'like', "%$filter%");
            });
        }

        if ($request->filled('season')) {
            $query->whereHas('application.season', function ($q) use ($request) {
                $q->where('slug', $request->season);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . " 00:00:00",
                $request->to . " 23:59:59"
            ]);
        }

        $returns = $query->latest()->get();

        $pdf = Pdf::loadView('admin.reports.monetary-returns-pdf', compact('returns'));

        return $pdf->download('monetary-returns-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
