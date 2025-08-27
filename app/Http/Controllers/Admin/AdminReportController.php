<?php

namespace App\Http\Controllers\Admin;

use App\Models\Season;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Exports\ApplicationsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminReportController extends Controller
{
    public function applications(Request $request)
    {
        $applications = Application::with(['farmer', 'season'])
            ->when(
                $request->filled('season_id'),
                fn($q) =>
                $q->where('season_id', $request->season_id)
            )
            ->when(
                $request->filled('reg_number'),
                fn($q) =>
                $q->whereHas(
                    'farmer',
                    fn($f) =>
                    $f->where('registration_number', 'like', '%' . $request->reg_number . '%')
                )
            )
            ->when(
                $request->filled('status'),
                fn($q) =>
                $q->where('status', $request->status)
            )
            ->when(
                $request->filled('from'),
                fn($q) =>
                $q->whereDate('created_at', '>=', $request->from)
            )
            ->when(
                $request->filled('to'),
                fn($q) =>
                $q->whereDate('created_at', '<=', $request->to)
            )
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $seasons = Season::all();

        return view('admin.reports.applications', compact('applications', 'seasons'));
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



}
