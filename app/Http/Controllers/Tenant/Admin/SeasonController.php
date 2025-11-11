<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Exports\EnhancedSeasonReportExport;
use App\Exports\SeasonReportPdfExport;
use App\Http\Controllers\Controller;
use App\Models\Commodity;
use App\Models\CommoditySeason;
use App\Models\Season;
use App\Services\SeasonReportService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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

        // Get all applications with relationships for detailed analysis
        $seasonApplications = $season->applications()
            ->with([
                'farmer:id,full_name,registration_number,phone',
                'farm:id,size',
                'commodity_allocations',
                'collectionVerification',
                'monetaryReturn'
            ])
            ->get();

        // Commodity distribution stats with available stock from allocations table
        $commodities = DB::table('commodities')
            ->join('commodity_allocations', 'commodities.name', '=', 'commodity_allocations.commodity_name')
            ->join('applications', 'commodity_allocations.application_id', '=', 'applications.id')
            ->where('applications.season_id', $season->id)
            ->select(
                'commodities.id',
                'commodities.name',
                'commodities.category',
                'commodities.unit',
                DB::raw('SUM(commodity_allocations.allocated_quantity) as allocated')
            )
            ->groupBy('commodities.id', 'commodities.name', 'commodities.category', 'commodities.unit')
            ->get()
            ->map(function ($commodity) use ($season) {
                // Get distributed quantity from collection_verifications array field
                $distributed = DB::table('collection_verifications')
                    ->join('applications', 'collection_verifications.application_id', '=', 'applications.id')
                    ->where('applications.season_id', $season->id)
                    ->get()
                    ->sum(function ($verification) use ($commodity) {
                        $quantities = $verification->collected_quantities;
                        // Since it's stored as JSON in DB but cast to array in model, we need to decode it here
                        if (is_string($quantities)) {
                            $quantities = json_decode($quantities, true);
                        }
                        foreach ($quantities as $allocationId => $data) {
                            if ($data['commodity_id'] == $commodity->id) {
                                return $data['collected_quantity'];
                            }
                        }
                        return 0;
                    });

                // Get available stock from allocations table
                $availableStock = \App\Models\Allocation::where('season_id', $season->id)
                    ->where('commodity_id', $commodity->id)
                    ->sum('available_stock');

                $commodity->distributed = $distributed;
                $commodity->remaining = ($commodity->allocated ?? 0) - $distributed;
                $commodity->available_stock = $availableStock;
                return $commodity;
            });

        // Totals
        $totalAllocated = $commodities->sum('allocated');
        $totalDistributed = $commodities->sum('distributed');
        $totalRemaining = $commodities->sum('remaining');
        $totalAvailableStock = $commodities->sum('available_stock');

        // Detailed farmer allocations and collections
        $farmerAllocations = $seasonApplications->map(function ($application) {
            $allocations = $application->commodity_allocations->map(function ($allocation) {
                return [
                    'commodity_name' => $allocation->commodity_name,
                    'allocated_quantity' => $allocation->allocated_quantity,
                    'collected_quantity' => 0, // Will be filled below
                ];
            });

            // Get collected quantities from collection verification
            if ($application->collectionVerification) {
                $collectedQuantities = $application->collectionVerification->collected_quantities;
                foreach ($allocations as &$allocation) {
                    foreach ($collectedQuantities as $allocationId => $data) {
                        if ($data['commodity_name'] === $allocation['commodity_name']) {
                            $allocation['collected_quantity'] = $data['collected_quantity'];
                            break;
                        }
                    }
                }
            }

            return [
                'farmer_name' => $application->farmer->full_name,
                'registration_number' => $application->farmer->registration_number,
                'farm_size' => $application->farm->size,
                'status' => $application->status,
                'allocations' => $allocations,
                'total_allocated' => $allocations->sum('allocated_quantity'),
                'total_collected' => $allocations->sum('collected_quantity'),
                'collection_status' => $application->collectionVerification ? 'collected' : 'pending',
                'payment_status' => $application->monetaryReturn ? $application->monetaryReturn->status : 'pending',
            ];
        });

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
        $totalLoanAmount = $seasonApplications->sum(function ($app) {
            return $app->total_loan ?? 0;
        });
        $coFundedPayments = $seasonApplications->where('equity', '>', 0)->sum('equity');
        $disbursedTotal = $seasonApplications->sum('disbursed_amount');
        $outstandingBalance = $totalLoanAmount - $disbursedTotal;

        $insuranceContributions = $seasonApplications->sum(function ($app) {
            return ($app->insurance_amount ?? 0) + ($app->equity ?? 0);
        });

        // Payment and collection status breakdown
        $pendingCollections = $seasonApplications->where('status', 'approved')
            ->where('collectionVerification', null)
            ->count();

        $completedCollections = $seasonApplications->where('status', 'approved')
            ->where('collectionVerification', '!=', null)
            ->count();

        $pendingPayments = $seasonApplications->where('loan_type', 'co-funded')
            ->filter(function ($app) {
                return $app->monetaryReturn === null || $app->monetaryReturn->status !== 'paid';
            })
            ->count();

        $completedPayments = $seasonApplications->where('loan_type', 'co-funded')
            ->where('monetaryReturn', '!=', null)
            ->filter(function ($app) {
                return $app->monetaryReturn && $app->monetaryReturn->status === 'paid';
            })
            ->count();

        // Application trends over the season period
        $seasonStart = \Carbon\Carbon::parse($season->start_date);
        $seasonEnd = \Carbon\Carbon::parse($season->end_date);

        // Get all applications for this season, regardless of creation date
        $applicationTrend = DB::table('applications')
            ->where('season_id', $season->id)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Group by weeks within the season
        $applicationTrendLabels = [];
        $applicationTrendData = [];
        $currentWeekStart = $seasonStart->copy()->startOfWeek();
        $seasonEndWeek = $seasonEnd->copy()->endOfWeek();

        $weekIndex = 1;
        while ($currentWeekStart->lte($seasonEndWeek)) {
            $weekEnd = $currentWeekStart->copy()->endOfWeek();

            $count = $applicationTrend->whereBetween('date', [$currentWeekStart->toDateString(), $weekEnd->toDateString()])->sum('count');

            $applicationTrendLabels[] = 'Week ' . $weekIndex;
            $applicationTrendData[] = $count;

            $currentWeekStart->addWeek();
            $weekIndex++;

            // Limit to reasonable number of weeks to prevent too many data points
            if ($weekIndex > 20) break;
        }

        // If no data, provide some default data to show the chart
        if (empty($applicationTrendData) || array_sum($applicationTrendData) === 0) {
            $applicationTrendLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            $applicationTrendData = [0, 0, 0, 0];
        }

        // Alerts
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
            'farmerAllocations',
            'totalApplications',
            'approvedApplications',
            'pendingApplications',
            'distributedApplications',
            'rejectedApplications',
            'totalFarmers',
            'totalAllocated',
            'totalDistributed',
            'totalRemaining',
            'totalAvailableStock',
            'collectionProgress',
            'daysRemainingInCollection',
            'distributionProgress',
            'daysUntilReturn',
            'totalLoanAmount',
            'coFundedPayments',
            'outstandingBalance',
            'insuranceContributions',
            'pendingCollections',
            'completedCollections',
            'pendingPayments',
            'completedPayments',
            'applicationTrendLabels',
            'applicationTrendData',
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
            Log::error('Excel export failed: ' . $e->getMessage());
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
            Log::info("Starting PDF export for season: {$season->name} with " . count($data) . " records");

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
            Log::error('PDF export failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
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
            Log::error('Direct PDF export failed: ' . $e->getMessage());
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
