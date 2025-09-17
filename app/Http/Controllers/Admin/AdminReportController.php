<?php

namespace App\Http\Controllers\Admin;

use App\Models\Season;
use App\Models\Application;
use App\Models\CollectionVerification;
use App\Models\ReturnVerification;
use Illuminate\Http\Request;
use App\Exports\ApplicationsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function applications(Request $request)
    {
        $perPage = 20; // Increased for better UX

        // Optimized query with selective loading
        $query = Application::with([
            'farmer:id,full_name,phone,registration_number',
            'season:id,name,type'
        ])->select([
            'id', 'farmer_id', 'season_id', 'status', 'total_loan',
            'disbursed_amount', 'created_at'
        ]);

        // Apply filters with optimized queries
        $query->when(
            $request->filled('season_id'),
            fn($q) => $q->where('season_id', $request->season_id)
        )->when(
            $request->filled('reg_number'),
            fn($q) => $q->whereHas(
                'farmer',
                fn($f) => $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
            )
        )->when(
            $request->filled('status'),
            fn($q) => $q->where('status', $request->status)
        )->when(
            $request->filled('from'),
            fn($q) => $q->whereDate('created_at', '>=', $request->from)
        )->when(
            $request->filled('to'),
            fn($q) => $q->whereDate('created_at', '<=', $request->to)
        );

        $applications = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Cache seasons data
        $seasons = cache()->remember('seasons_list_reports', 1800, function () {
            return Season::select('id', 'name', 'type')->get();
        });

        // Calculate statistics with optimized queries
        $statistics = cache()->remember('reports_statistics_' . md5(serialize($request->all())), 300, function () use ($request) {
            $baseQuery = Application::query();

            // Apply same filters for statistics
            $baseQuery->when($request->filled('season_id'), fn($q) => $q->where('season_id', $request->season_id))
                ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
                ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
                ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to));

            return [
                'total' => $baseQuery->count(),
                'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
                'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
                'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
                'total_loan' => (clone $baseQuery)->sum('total_loan'),
                'total_disbursed' => (clone $baseQuery)->sum('disbursed_amount'),
            ];
        });

        return view('admin.reports.applications', compact('applications', 'seasons', 'statistics'));
    }

    public function export(Request $request): StreamedResponse
{
    $applications = Application::with(['farmer', 'season'])
        ->when($request->filled('season_id'), fn($q) => $q->where('season_id', $request->season_id))
        ->when($request->filled('reg_number'), fn($q) =>
            $q->whereHas('farmer', fn($f) =>
                $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
            )
        )
        ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
        ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
        ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to))
        ->orderBy('created_at', 'desc')
        ->get();

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=applications_report.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $columns = ['Reg. No', 'Farmer', 'Season', 'Total Loan', 'Status', 'Created At'];

    $callback = function() use ($applications, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($applications as $app) {
            fputcsv($file, [
                $app->farmer->registration_number,
                $app->farmer->full_name,
                $app->season->name,
                $app->total_loan,
                ucfirst($app->status),
                $app->created_at->format('Y-m-d'),
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
public function exportExcel(Request $request)
{
    $applications = Application::with(['farmer', 'season'])
        ->when($request->filled('season_id'), fn($q) => $q->where('season_id', $request->season_id))
        ->when($request->filled('reg_number'), fn($q) =>
            $q->whereHas('farmer', fn($f) =>
                $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
            )
        )
        ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
        ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
        ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to))
        ->orderBy('created_at', 'desc')
        ->get();

    return Excel::download(new ApplicationsExport($applications), 'applications_report.xlsx');
}

    /**
     * Collection Reports - Shows approved collection verifications
     */
    public function collections(Request $request)
    {
        $perPage = 20;

        // Query for approved collection verifications
        $query = CollectionVerification::with([
            'application.farmer:id,full_name,phone,registration_number',
            'application.season:id,name,type',
            'agent.user:id,name,email'
        ])->where('status', 'approved')
        ->select(['id', 'application_id', 'agent_id', 'status', 'created_at', 'updated_at']);

        // Apply filters
        $query->when(
            $request->filled('season_id'),
            fn($q) => $q->whereHas('application', fn($app) => $app->where('season_id', $request->season_id))
        )->when(
            $request->filled('reg_number'),
            fn($q) => $q->whereHas('application.farmer', fn($f) =>
                $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
            )
        )->when(
            $request->filled('from'),
            fn($q) => $q->whereDate('created_at', '>=', $request->from)
        )->when(
            $request->filled('to'),
            fn($q) => $q->whereDate('created_at', '<=', $request->to)
        );

        $collections = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Cache seasons data
        $seasons = cache()->remember('seasons_list_reports', 1800, function () {
            return Season::select('id', 'name', 'type')->get();
        });

        // Calculate statistics
        $statistics = cache()->remember('collections_statistics_' . md5(serialize($request->all())), 300, function () use ($request) {
            $baseQuery = CollectionVerification::where('status', 'approved');

            $baseQuery->when($request->filled('season_id'), fn($q) =>
                $q->whereHas('application', fn($app) => $app->where('season_id', $request->season_id))
            )->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to));

            return [
                'total_collections' => $baseQuery->count(),
                'total_farmers' => (clone $baseQuery)->distinct('application_id')->count(),
                'total_loan_amount' => (clone $baseQuery)->with('application')->get()->sum('application.total_loan'),
                'total_disbursed_amount' => (clone $baseQuery)->with('application')->get()->sum('application.disbursed_amount'),
                'collections_this_month' => (clone $baseQuery)->whereMonth('created_at', now()->month)->count(),
            ];
        });

        return view('admin.reports.collections', compact('collections', 'seasons', 'statistics'));
    }

    /**
     * Return Reports - Shows approved return verifications
     */
    public function returns(Request $request)
    {
        $perPage = 20;

        // Query for approved return verifications
        $query = ReturnVerification::with([
            'application.farmer:id,full_name,phone,registration_number',
            'application.season:id,name,type',
            'agent.user:id,name,email'
        ])->where('status', 'approved')
        ->select(['id', 'application_id', 'agent_id', 'status', 'created_at', 'updated_at']);

        // Apply filters
        $query->when(
            $request->filled('season_id'),
            fn($q) => $q->whereHas('application', fn($app) => $app->where('season_id', $request->season_id))
        )->when(
            $request->filled('reg_number'),
            fn($q) => $q->whereHas('application.farmer', fn($f) =>
                $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
            )
        )->when(
            $request->filled('from'),
            fn($q) => $q->whereDate('created_at', '>=', $request->from)
        )->when(
            $request->filled('to'),
            fn($q) => $q->whereDate('created_at', '<=', $request->to)
        );

        $returns = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Cache seasons data
        $seasons = cache()->remember('seasons_list_reports', 1800, function () {
            return Season::select('id', 'name', 'type')->get();
        });

        // Calculate statistics
        $statistics = cache()->remember('returns_statistics_' . md5(serialize($request->all())), 300, function () use ($request) {
            $baseQuery = ReturnVerification::where('status', 'approved');

            $baseQuery->when($request->filled('season_id'), fn($q) =>
                $q->whereHas('application', fn($app) => $app->where('season_id', $request->season_id))
            )->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to));

            return [
                'total_returns' => $baseQuery->count(),
                'total_farmers' => (clone $baseQuery)->distinct('application_id')->count(),
                'total_loan_amount' => (clone $baseQuery)->with('application')->get()->sum('application.total_loan'),
                'total_disbursed_amount' => (clone $baseQuery)->with('application')->get()->sum('application.disbursed_amount'),
                'returns_this_month' => (clone $baseQuery)->whereMonth('created_at', now()->month)->count(),
            ];
        });

        return view('admin.reports.returns', compact('returns', 'seasons', 'statistics'));
    }

    /**
     * Export Collections Report
     */
    public function exportCollections(Request $request): StreamedResponse
    {
        $collections = CollectionVerification::with(['application.farmer', 'application.season', 'agent.user'])
            ->where('status', 'approved')
            ->when($request->filled('season_id'), fn($q) =>
                $q->whereHas('application', fn($app) => $app->where('season_id', $request->season_id))
            )
            ->when($request->filled('reg_number'), fn($q) =>
                $q->whereHas('application.farmer', fn($f) =>
                    $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
                )
            )
            ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=collections_report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Reg. No', 'Farmer', 'Season', 'Total Loan', 'Disbursed Amount', 'Agent', 'Collection Date', 'Status'];

        $callback = function() use ($collections, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($collections as $collection) {
                fputcsv($file, [
                    $collection->application->farmer->registration_number,
                    $collection->application->farmer->full_name,
                    $collection->application->season->name,
                    $collection->application->total_loan,
                    $collection->application->disbursed_amount,
                    $collection->agent->user->name ?? 'N/A',
                    $collection->created_at->format('Y-m-d'),
                    ucfirst($collection->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Returns Report
     */
    public function exportReturns(Request $request): StreamedResponse
    {
        $returns = ReturnVerification::with(['application.farmer', 'application.season', 'agent.user'])
            ->where('status', 'approved')
            ->when($request->filled('season_id'), fn($q) =>
                $q->whereHas('application', fn($app) => $app->where('season_id', $request->season_id))
            )
            ->when($request->filled('reg_number'), fn($q) =>
                $q->whereHas('application.farmer', fn($f) =>
                    $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
                )
            )
            ->when($request->filled('from'), fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=returns_report.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Reg. No', 'Farmer', 'Season', 'Total Loan', 'Disbursed Amount', 'Agent', 'Return Date', 'Status'];

        $callback = function() use ($returns, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->application->farmer->registration_number,
                    $return->application->farmer->full_name,
                    $return->application->season->name,
                    $return->application->total_loan,
                    $return->application->disbursed_amount,
                    $return->agent->user->name ?? 'N/A',
                    $return->created_at->format('Y-m-d'),
                    ucfirst($return->status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
