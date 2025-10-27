<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Models\Season;
use App\Models\Commodity;
use Illuminate\Http\Request;
use App\Models\CommoditySeason;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Validation\ValidationException;
use App\Services\SeasonReportService;
use App\Exports\EnhancedSeasonReportExport;
use App\Exports\SeasonReportPdfExport;
use Maatwebsite\Excel\Facades\Excel;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seasons = Season::with('commodities')->get();
        return view('admin.seasons.index', compact('seasons'));
    }

    public function create()
    {
        $commodities = Commodity::all();

        if ($commodities->isEmpty()) {
            ToastMagic::success('You must create at least one commodity before creating a season.');
            return redirect()->route('admin.commodities.create');
        }
        return view('admin.seasons.create', compact('commodities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:dry,wet',
            'loan_type' => 'required|in:co-funded,complete-loan',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'collection_start_date' => 'required|date|after:end_date',
            'collection_end_date' => 'required|date|after:collection_start_date',
            'return_deadline' => 'nullable|date|after:end_date',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'nullable|integer|min:1',
            'status' => 'nullable|in:open,closed',
            'commodities' => 'required|array|min:1',
            'commodities.*' => 'exists:commodities,id',
        ]);

        // Conditional requirements based on loan_type
        if ($data['loan_type'] === 'complete-loan') {
            $request->validate([
                'return_deadline' => 'required|date|after:end_date',
                'send_reminder_after_days' => 'required|integer|min:1',
            ]);
        } else {
            // co-funded: ensure these are null to avoid confusion
            $data['return_deadline'] = null;
            $data['send_reminder_after_days'] = null;
        }

        $year = \Carbon\Carbon::parse($request->start_date)->year;

        $exists = Season::whereYear('start_date', $year)
            ->where('type', $data['type']) // ✅ use validated type
            ->exists();

        if ($exists) {
            ToastMagic::error("A {$data['type']} season already exists for the year {$year}.");
            return redirect()->back()->withInput(); // ✅ stop execution
        }

        // Determine status based on start date
        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $isStartingToday = $startDate->isToday();

        // Set status based on start date
        $data['status'] = $isStartingToday ? 'open' : 'closed';

        // Only close other open seasons if we're creating a new open season
        if ($isStartingToday) {
            $openSeasons = Season::where('status', 'open')->get();
            if ($openSeasons->isNotEmpty()) {
                Season::where('status', 'open')->update(['status' => 'closed']);
                $seasonNames = $openSeasons->pluck('name')->join(', ');
                ToastMagic::info("Closed existing open season(s): {$seasonNames}");
            }
            ToastMagic::success('New season created and opened.');
        } else {
            ToastMagic::success('New season created. It will open on its start date.');
        }

        $season = Season::create($data);

        try {
            $season->commodities()->sync($request->commodities);
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error('An error occurred while assigning commodities. Please try again or contact support.');
        }

        ToastMagic::success('Season created.');
        return redirect()->route('admin.seasons.index');
    }


    public function show(Season $season)
    {
        // Application stats
        $applications = $season->applications();
        $totalApplications = $applications->count();
        $approvedApplications = $applications->clone()->where('status', 'approved')->count();
        $pendingApplications = $applications->clone()->where('status', 'pending')->count();
        $distributedApplications = $applications->clone()->where('status', 'distributed')->count();
        $rejectedApplications = $applications->clone()->where('status', 'rejected')->count();
        $totalFarmers = $applications->clone()->distinct('farmer_id')->count('farmer_id');

        // Commodity distribution stats
        $commodities = DB::table('commodities')
            ->join('commodity_allocations', 'commodities.name', '=', 'commodity_allocations.commodity_name')
            ->join('applications', 'commodity_allocations.application_id', '=', 'applications.id')
            ->leftJoin('collection_verifications', 'commodity_allocations.application_id', '=', 'collection_verifications.application_id')
            ->where('applications.season_id', $season->id)
            ->select(
                'commodities.id',
                'commodities.name',
                'commodities.category',
                'commodities.unit',
                DB::raw('SUM(commodity_allocations.allocated_quantity) as allocated'),
                DB::raw('SUM(CASE WHEN collection_verifications.id IS NOT NULL THEN commodity_allocations.allocated_quantity ELSE 0 END) as distributed')
            )
            ->groupBy('commodities.id', 'commodities.name', 'commodities.category', 'commodities.unit')
            ->get()
            ->map(function ($item) {
                $item->remaining = ($item->allocated ?? 0) - ($item->distributed ?? 0);
                return $item;
            });

        // Totals
        $totalAllocated = $commodities->sum('allocated');
        $totalDistributed = $commodities->sum('distributed');
        $totalRemaining = $commodities->sum('remaining');

        // Season progress calculations
        $collectionStart = \Carbon\Carbon::parse($season->collection_start_date);
        $collectionEnd = \Carbon\Carbon::parse($season->collection_end_date);
        $now = \Carbon\Carbon::now();

        $totalCollectionDays = $collectionEnd->diffInDays($collectionStart);
        $elapsedDays = $collectionStart->diffInDays($now, false); // false for signed
        $collectionProgress = $totalCollectionDays > 0 ? min(100, max(0, ($elapsedDays / $totalCollectionDays) * 100)) : 0;
        $daysRemainingInCollection = $collectionEnd->isPast() ? 0 : $now->diffInDays($collectionEnd);

        $distributionProgress = $totalAllocated > 0 ? round(($totalDistributed / $totalAllocated) * 100, 1) : 0;

        $daysUntilReturn = $season->loan_type === 'complete-loan' && $season->return_deadline
            ? (\Carbon\Carbon::parse($season->return_deadline)->isPast()
                ? 0
                : $now->diffInDays(\Carbon\Carbon::parse($season->return_deadline)))
            : null;

        // Financial summary
        $seasonApplications = $season->applications()->with('applicationCommodities.commodity')->get();
        $totalLoanAmount = $seasonApplications->sum(function ($app) {
            return $app->total_loan ?? 0;
        });
        $coFundedPayments = $seasonApplications->where('equity', '>', 0)->sum('equity');
        $disbursedTotal = $seasonApplications->sum('disbursed_amount');
        $outstandingBalance = $totalLoanAmount - $disbursedTotal;

        $insuranceContributions = $seasonApplications->sum(function ($app) {
            return ($app->insurance_amount ?? 0) + ($app->equity ?? 0);
        });

        // Application trends (last 8 weeks)
        $weeksAgo = \Carbon\Carbon::now()->subWeeks(8);
        $applicationTrend = DB::table('applications')
            ->where('season_id', $season->id)
            ->where('created_at', '>=', $weeksAgo)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Group by weeks
        $applicationTrendLabels = [];
        $applicationTrendData = [];
        $currentWeekStart = $weeksAgo->copy()->startOfWeek();

        for ($i = 0; $i < 8; $i++) {
            $weekStart = $currentWeekStart->copy()->addWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();

            $count = $applicationTrend->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])->sum('count');

            $applicationTrendLabels[] = 'Week ' . ($i + 1);
            $applicationTrendData[] = $count;
        }

        // Alerts
        $pendingCollections = \App\Models\Application::where('season_id', $season->id)
            ->where('status', 'approved')
            ->whereDoesntHave('collectionVerification')
            ->count();

        $overdueReturns = 0;
        // TODO: Implement overdue returns check when return system is complete
        // For now, assume no overdue returns
        // if ($season->loan_type === 'complete-loan' && $season->return_deadline) {
        //     $overdueReturns = \App\Models\Application::where('season_id', $season->id)
        //         ->where('status', 'distributed')
        //         ->whereDoesntHave('returnVerification')
        //         ->where('return_date', '<', $now)
        //         ->count();
        // }

        return view('admin.seasons.show', compact(
            'season',
            'commodities',
            'totalApplications',
            'approvedApplications',
            'pendingApplications',
            'distributedApplications',
            'rejectedApplications',
            'totalFarmers',
            'totalAllocated',
            'totalDistributed',
            'totalRemaining',
            'collectionProgress',
            'daysRemainingInCollection',
            'distributionProgress',
            'daysUntilReturn',
            'totalLoanAmount',
            'coFundedPayments',
            'outstandingBalance',
            'insuranceContributions',
            'applicationTrendLabels',
            'applicationTrendData',
            'pendingCollections',
            'overdueReturns'
        ));
    }

    public function edit(Season $season)
    {
        $commodities = Commodity::all();
        $selected = $season->commodities()->pluck('commodities.id')->toArray();
        return view('admin.seasons.edit', compact('season', 'commodities', 'selected'));
    }

    public function update(Request $request, Season $season)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:dry,wet',
            'loan_type' => 'required|in:co-funded,complete-loan',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'collection_start_date' => 'required|date|after:end_date',
            'collection_end_date' => 'required|date|after:collection_start_date',
            'return_deadline' => 'nullable|date|after:end_date',
            'insurance_rate' => 'required|numeric|min:0|max:100',
            'send_reminder_after_days' => 'nullable|integer|min:1',
            'status' => 'nullable|in:open,closed',
            'commodities' => 'required|array|min:1',
            'commodities.*' => 'exists:commodities,id',
        ]);

        if ($data['loan_type'] === 'complete-loan') {
            $request->validate([
                'return_deadline' => 'required|date|after:end_date',
                'send_reminder_after_days' => 'required|integer|min:1',
            ]);
        } else {
            $data['return_deadline'] = null;
            $data['send_reminder_after_days'] = null;
        }

        $year = \Carbon\Carbon::parse($data['start_date'])->year;

        // ✅ Ensure no duplicate season of same type + year, excluding current season
        $exists = Season::whereYear('start_date', $year)
            ->where('type', $data['type'])
            ->where('id', '!=', $season->id)
            ->exists();

        if ($exists) {
            ToastMagic::error("A {$data['type']} season already exists for the year {$year}.");
            return redirect()->back()->withInput();
        }

        // If updating to open status, close any other open seasons
        if (($data['status'] ?? $season->status) === 'open' && $season->status !== 'open') {
            $openSeasons = Season::where('status', 'open')->where('id', '!=', $season->id)->get();
            if ($openSeasons->count() > 0) {
                Season::where('status', 'open')->where('id', '!=', $season->id)->update(['status' => 'closed']);
                $seasonNames = $openSeasons->pluck('name')->join(', ');
                ToastMagic::info("Closed existing open season(s): {$seasonNames}");
            }
        }

        $season->update($data);

        try {
            $season->commodities()->sync($request->commodities);
        } catch (\Throwable $e) {
            report($e);
            ToastMagic::error('An error occurred while updating commodities. Please try again.');
            return redirect()->back()->withInput();
        }

        ToastMagic::success('Season updated successfully.');
        return redirect()->route('admin.seasons.index');
    }

    public function destroy(Season $season)
    {
        $season->delete();
        return back()->with('success', 'Season deleted');
    }

    /**
     * Export season report as Excel
     */
    public function exportExcel(Season $season)
    {
        try {
            $reportService = new SeasonReportService($season);
            $data = $reportService->generateReportData();
            $summary = $reportService->getSummary();
            
            $fileName = 'season_report_' . now()->format('Y-m-d') . '.xlsx';
            
            return Excel::download(
                new EnhancedSeasonReportExport($data, $summary),
                $fileName
            );
            
        } catch (\Exception $e) {
            \Log::error('Excel export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate Excel export: ' . $e->getMessage());
        }
    }

    /**
     * Export season report as PDF
     */
    public function exportPdf(Season $season)
    {
        try {
            $reportService = new SeasonReportService($season);
            $data = $reportService->generateReportData();
            $summary = $reportService->getSummary();
            
            // Log the export attempt
            \Log::info("Starting PDF export for season: {$season->name} with " . count($data) . " records");
            
            $pdfExporter = new SeasonReportPdfExport($data, $summary);
            $fileName = 'season_report_' . $season->name . '_' . now()->format('Y-m-d') . '.pdf';
            
            // For large datasets, show a message to the user
            if (count($data) > 50) {
                return back()
                    ->with('info', 'Generating PDF for a large dataset. This may take a moment. The download will start automatically.')
                    ->with('auto_download', route('admin.seasons.export.pdf.direct', $season->uuid));
            }
            
            return $pdfExporter->download($fileName);
            
        } catch (\Exception $e) {
            \Log::error('PDF export failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Failed to generate PDF export. Please try again or contact support if the problem persists.');
        }
    }
    
    /**
     * Direct download endpoint for large exports
     */
    public function exportPdfDirect($uuid)
    {
        try {
            $season = Season::where('uuid', $uuid)->firstOrFail();
            $reportService = new SeasonReportService($season);
            $data = $reportService->generateReportData();
            $summary = $reportService->getSummary();
            
            $pdfExporter = new SeasonReportPdfExport($data, $summary);
            $fileName = 'season_report_' . $season->name . '_' . now()->format('Y-m-d') . '.pdf';
            
            return $pdfExporter->download($fileName);
            
        } catch (\Exception $e) {
            \Log::error('Direct PDF export failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate PDF. Please try again.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // Keeping this for backward compatibility
    public function export($uuid)
    {
        $season = Season::where('uuid', $uuid)->firstOrFail();
        return $this->exportExcel($season);
    }
    public function close(Season $season)
    {
        $season->update(['status' => 'closed']);
        ToastMagic::success('Season closed.');
        return back();
    }

    public function reopen(Season $season)
    {
        // Check if there's already an open season
        $openSeason = Season::where('status', 'open')->where('id', '!=', $season->id)->first();

        if ($openSeason) {
            ToastMagic::error("Cannot reopen season. '{$openSeason->name}' is already open. Please close it first.");
            return back();
        }

        $season->update(['status' => 'open']);
        ToastMagic::success('Season reopened.');
        return back();
    }
}
