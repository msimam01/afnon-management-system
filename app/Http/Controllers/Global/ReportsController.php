<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use App\Models\GlobalSeason;
use App\Models\SuperAdmin\Tenant;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        $seasons = GlobalSeason::orderBy('created_at', 'desc')->get();
        $tenants = Tenant::all();

        return view('global.reports.index', compact('seasons', 'tenants'));
    }

    /**
     * Display the season allocation report.
     */
    public function seasonAllocation(Request $request)
    {
        $request->validate([
            'season_uuid' => 'required|string|exists:global_seasons,uuid'
        ]);

        $season = GlobalSeason::where('uuid', $request->season_uuid)->first();
        $reportData = $this->reportService->generateSeasonAllocationReport($request->season_uuid);

        return view('global.reports.season-allocation', compact('season', 'reportData'));
    }

    /**
     * Display the tenant distribution report.
     */
    public function tenantDistribution(Request $request)
    {
        $request->validate([
            'season_uuid' => 'required|string|exists:global_seasons,uuid',
            'tenant_id' => 'required|string|exists:tenants,id'
        ]);

        $season = GlobalSeason::where('uuid', $request->season_uuid)->first();
        $tenant = Tenant::find($request->tenant_id);

        // Get comprehensive allocation summary
        $allocationSummary = $this->getTenantAllocationSummary($season, $tenant);
        $reportData = $this->reportService->generateTenantDistributionReport($request->season_uuid, $request->tenant_id);

        return view('global.reports.tenant-distribution', compact('season', 'tenant', 'allocationSummary', 'reportData'));
    }

    /**
     * Display the return compliance report.
     */
    public function returnCompliance(Request $request)
    {
        $request->validate([
            'season_uuid' => 'required|string|exists:global_seasons,uuid'
        ]);

        $season = GlobalSeason::where('uuid', $request->season_uuid)->first();
        $reportData = $this->reportService->generateReturnComplianceReport($request->season_uuid);

        return view('global.reports.return-compliance', compact('season', 'reportData'));
    }

    /**
     * Export season allocation report as CSV.
     */
    public function exportSeasonAllocation(Request $request)
    {
        $request->validate([
            'season_uuid' => 'required|string|exists:global_seasons,uuid',
            'format' => 'required|in:csv,pdf'
        ]);

        $reportData = $this->reportService->generateSeasonAllocationReport($request->season_uuid);

        if (!$reportData) {
            return back()->with('error', 'Report data not found');
        }

        if ($request->format === 'csv') {
            return $this->exportSeasonAllocationCSV($reportData);
        }

        // For PDF, we'd need a PDF library like TCPDF or DomPDF
        return back()->with('error', 'PDF export not implemented yet');
    }

    /**
     * Export season allocation as CSV
     */
    protected function exportSeasonAllocationCSV($reportData)
    {
        $filename = "season_allocation_{$reportData['season_name']}_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($reportData) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Season Allocation Report']);
            fputcsv($file, ['Season', $reportData['season_name']]);
            fputcsv($file, ['Season Type', ucfirst($reportData['season_type'])]);
            fputcsv($file, ['Loan Type', ucfirst(str_replace('-', ' ', $reportData['loan_type']))]);
            fputcsv($file, ['Budget', number_format($reportData['budget'], 2)]);
            fputcsv($file, ['Generated', now()->toDateTimeString()]);
            fputcsv($file, []);

            // Commodities section
            fputcsv($file, ['=== COMMODITY OVERVIEW ===']);
            fputcsv($file, [
                'Commodity', 'Category', 'Unit', 'Total Global Stock',
                'Allocated to Tenants', 'Remaining in Global', 'Percentage Allocated'
            ]);

            foreach ($reportData['commodities'] as $commodity) {
                fputcsv($file, [
                    $commodity['name'],
                    $commodity['category'],
                    $commodity['unit'],
                    number_format($commodity['total_global_stock'], 2),
                    number_format($commodity['allocated_to_tenants'], 2),
                    number_format($commodity['remaining_in_global'], 2),
                    $commodity['percentage_allocated'] . '%'
                ]);
            }

            fputcsv($file, []);

            // Tenants section
            fputcsv($file, ['=== TENANT ALLOCATIONS & DISTRIBUTION STATS ===']);
            foreach ($reportData['tenants'] as $tenant) {
                fputcsv($file, ['Tenant', $tenant['tenant_name']]);

                if (isset($tenant['distribution_stats'])) {
                    fputcsv($file, ['  Total Applications', $tenant['distribution_stats']['total_applications'] ?? 0]);
                    fputcsv($file, ['  Approved Applications', $tenant['distribution_stats']['approved_applications'] ?? 0]);
                    fputcsv($file, ['  Collected Applications', $tenant['distribution_stats']['collected_applications'] ?? 0]);
                    if (isset($tenant['distribution_stats']['returned_applications'])) {
                        fputcsv($file, ['  Returned Applications', $tenant['distribution_stats']['returned_applications']]);
                    }
                }

                fputcsv($file, ['  Commodity', 'Allocated Stock']);
                foreach ($tenant['allocations'] as $allocation) {
                    fputcsv($file, ['  ' . $allocation['commodity'], number_format($allocation['allocated_stock'], 2)]);
                }
                fputcsv($file, []);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export tenant distribution report as CSV.
     */
    public function exportTenantDistribution(Request $request)
    {
        $request->validate([
            'season_uuid' => 'required|string|exists:global_seasons,uuid',
            'tenant_id' => 'required|string|exists:tenants,id',
            'format' => 'required|in:csv,pdf'
        ]);

        $reportData = $this->reportService->generateTenantDistributionReport($request->season_uuid, $request->tenant_id);

        if (!$reportData) {
            return back()->with('error', 'Report data not found');
        }

        $season = GlobalSeason::where('uuid', $request->season_uuid)->first();
        $tenant = Tenant::find($request->tenant_id);

        // Get comprehensive allocation summary
        $allocationSummary = $this->getTenantAllocationSummary($season, $tenant);

        if ($request->format === 'csv') {
            return $this->exportTenantDistributionCSV($reportData, $season, $tenant);
        }

        // PDF export
        return $this->exportTenantDistributionPDF($allocationSummary, $reportData, $season, $tenant);
    }

    /**
     * Export tenant distribution as CSV with comprehensive data
     */
    protected function exportTenantDistributionCSV($reportData, $season, $tenant)
    {
        $filename = "tenant_distribution_{$tenant->id}_{$season->name}_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($reportData, $season, $tenant) {
            $file = fopen('php://output', 'w');

            // Header information
            fputcsv($file, ['Tenant Distribution Report']);
            fputcsv($file, ['Season', $season->name]);
            fputcsv($file, ['Tenant', $tenant->id]);
            fputcsv($file, ['Loan Type', ucfirst(str_replace('-', ' ', $reportData['loan_type']))]);
            fputcsv($file, ['Generated', now()->toDateTimeString()]);
            fputcsv($file, []);

            // Summary section
            fputcsv($file, ['=== SUMMARY ===']);
            fputcsv($file, ['Total Farmers', $reportData['summary']['total_farmers']]);
            fputcsv($file, ['Farmers Collected', $reportData['summary']['farmers_collected']]);
            if ($reportData['loan_type'] === 'complete-loan') {
                fputcsv($file, ['Farmers Returned', $reportData['summary']['farmers_returned'] ?? 0]);
            }
            fputcsv($file, []);

            // Commodity distribution section
            fputcsv($file, ['=== COMMODITY DISTRIBUTION ===']);
            $commodityHeaders = [
                'Commodity', 'Unit', 'Original Allocated', 'Approved Quantity',
                'Distributed Quantity', 'Collected Quantity', 'Collection Variance', 'Remaining Stock'
            ];

            if ($reportData['loan_type'] === 'complete-loan') {
                $commodityHeaders = array_merge($commodityHeaders, [
                    'Expected Return', 'Actual Returned', 'Return Variance', 'Partial Returns'
                ]);
            }

            fputcsv($file, $commodityHeaders);

            foreach ($reportData['commodity_distribution'] as $commodity) {
                $row = [
                    $commodity['commodity_name'],
                    $commodity['unit'],
                    number_format($commodity['original_allocated'], 2),
                    number_format($commodity['approved_quantity'], 2),
                    number_format($commodity['distributed_quantity'], 2),
                    number_format($commodity['collected_quantity'], 2),
                    number_format($commodity['collection_variance'], 2),
                    number_format($commodity['remaining_stock'], 2),
                ];

                if ($reportData['loan_type'] === 'complete-loan' && isset($commodity['return_metrics'])) {
                    $row = array_merge($row, [
                        number_format($commodity['return_metrics']['expected_return'], 2),
                        number_format($commodity['return_metrics']['actual_returned'], 2),
                        number_format($commodity['return_metrics']['return_variance'], 2),
                        $commodity['return_metrics']['partial_returns'],
                    ]);
                }

                fputcsv($file, $row);
            }

            fputcsv($file, []);

            // Farmer distribution section
            fputcsv($file, ['=== FARMER DISTRIBUTION ===']);
            $farmerHeaders = [
                'Farmer ID', 'Farmer Name', 'Registration Number', 'Payment Status',
                'Total Allocated', 'Total Collected'
            ];

            if ($reportData['loan_type'] === 'complete-loan') {
                $farmerHeaders = array_merge($farmerHeaders, [
                    'Total Returned', 'Expected Return', 'Return Variance'
                ]);
            }

            $farmerHeaders[] = 'Commodities Detail';
            fputcsv($file, $farmerHeaders);

            foreach ($reportData['farmer_distributions'] as $farmer) {
                // Build commodities detail string
                $commoditiesDetail = collect($farmer['commodities'])->map(function($c) {
                    return "{$c['name']}(Alloc:{$c['allocated']}/Coll:{$c['collected']})";
                })->implode(' | ');

                $row = [
                    $farmer['farmer_id'],
                    $farmer['farmer_name'],
                    $farmer['registration_number'],
                    $farmer['payment_status'],
                    number_format($farmer['total_allocated'], 2),
                    number_format($farmer['total_collected'], 2),
                ];

                if ($reportData['loan_type'] === 'complete-loan') {
                    $row = array_merge($row, [
                        number_format($farmer['total_returned'] ?? 0, 2),
                        number_format($farmer['expected_return'] ?? 0, 2),
                        number_format($farmer['return_variance'] ?? 0, 2),
                    ]);
                }

                $row[] = $commoditiesDetail;
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export return compliance report as CSV.
     */
    public function exportReturnCompliance(Request $request)
    {
        $request->validate([
            'season_uuid' => 'required|string|exists:global_seasons,uuid',
            'format' => 'required|in:csv,pdf'
        ]);

        $reportData = $this->reportService->generateReturnComplianceReport($request->season_uuid);

        if (!$reportData) {
            return back()->with('error', 'Report data not found');
        }

        if ($request->format === 'csv') {
            return $this->exportReturnComplianceCSV($reportData, $request->season_uuid);
        }

        return back()->with('error', 'PDF export not implemented yet');
    }

    /**
     * Export return compliance as CSV
     */
    protected function exportReturnComplianceCSV($reportData, $seasonUuid)
    {
        $season = GlobalSeason::where('uuid', $seasonUuid)->first();
        $filename = "return_compliance_{$season->name}_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($reportData, $season) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Return Compliance Report']);
            fputcsv($file, ['Season', $season->name]);
            fputcsv($file, ['Loan Type', 'Complete Loan']);
            fputcsv($file, ['Generated', now()->toDateTimeString()]);
            fputcsv($file, []);

            // Main data
            fputcsv($file, ['=== TENANT RETURN COMPLIANCE ===']);
            fputcsv($file, [
                'Tenant Name', 'Total Expected Returns', 'Total Returned', 'Variance',
                'Compliance Rate (%)', 'Total Return Records', 'Partial Returns', 'Overdue Applications'
            ]);

            foreach ($reportData as $row) {
                fputcsv($file, [
                    $row['tenant_name'],
                    number_format($row['total_expected_returns'], 2),
                    number_format($row['total_returned'], 2),
                    number_format($row['variance'], 2),
                    $row['compliance_rate'],
                    $row['total_return_records'],
                    $row['partial_returns'],
                    $row['overdue_applications']
                ]);
            }

            // Shortfall reasons summary
            fputcsv($file, []);
            fputcsv($file, ['=== SHORTFALL REASONS SUMMARY ===']);

            foreach ($reportData as $row) {
                if (!empty($row['shortfall_reasons'])) {
                    fputcsv($file, ['Tenant', $row['tenant_name']]);
                    fputcsv($file, ['Reason', 'Count']);
                    foreach ($row['shortfall_reasons'] as $reason => $count) {
                        fputcsv($file, ['  ' . $reason, $count]);
                    }
                    fputcsv($file, []);
                }
            }

            // Overall statistics
            fputcsv($file, []);
            fputcsv($file, ['=== OVERALL STATISTICS ===']);
            $totalExpected = array_sum(array_column($reportData, 'total_expected_returns'));
            $totalReturned = array_sum(array_column($reportData, 'total_returned'));
            $totalVariance = array_sum(array_column($reportData, 'variance'));
            $overallCompliance = $totalExpected > 0 ? round(($totalReturned / $totalExpected) * 100, 2) : 0;
            $totalOverdue = array_sum(array_column($reportData, 'overdue_applications'));

            fputcsv($file, ['Total Expected Returns (All Tenants)', number_format($totalExpected, 2)]);
            fputcsv($file, ['Total Actually Returned (All Tenants)', number_format($totalReturned, 2)]);
            fputcsv($file, ['Total Variance (All Tenants)', number_format($totalVariance, 2)]);
            fputcsv($file, ['Overall Compliance Rate', $overallCompliance . '%']);
            fputcsv($file, ['Total Overdue Applications', $totalOverdue]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export farmers data (for all tenants or specific tenant in a season)
     */
    public function exportFarmers(Request $request)
    {
        $request->validate([
            'season_uuid' => 'required|exists:global_seasons,uuid',
            'format'      => 'required|in:csv,pdf',
            'tenant_id'   => 'nullable|exists:tenants,id',
        ]);

        $season = GlobalSeason::where('uuid', $request->season_uuid)->first();
        $data = collect();

        $tenants = $request->tenant_id
            ? Tenant::where('id', $request->tenant_id)->get()
            : Tenant::all();

        foreach ($tenants as $tenant) {
            $report = $this->reportService->generateTenantDistributionReport(
                $request->season_uuid,
                $tenant->id
            );
            if ($report && isset($report['farmer_distributions'])) {
                $data = $data->merge($report['farmer_distributions']);
            }
        }

        if ($request->format === 'csv') {
            return $this->exportFarmersCSV($data, $season, $request->tenant_id);
        }

        // PDF implementation placeholder
        return back()->with('error', 'PDF export not ready');
    }

    /**
     * Export farmers as CSV
     */
    protected function exportFarmersCSV($farmers, $season, $tenantId = null)
    {
        $tenantName = $tenantId ? Tenant::find($tenantId)->name : 'All_Tenants';
        $filename = "farmers_{$season->name}_{$tenantName}_" . date('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($farmers, $season) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Farmers Distribution Report']);
            fputcsv($file, ['Season', $season->name]);
            fputcsv($file, ['Generated', now()->toDateTimeString()]);
            fputcsv($file, []);

            // Column headers
            $headers = [
                'Farmer ID', 'Farmer Name', 'Registration Number', 'Payment Status',
                'Total Allocated', 'Total Collected'
            ];

            if ($season->loan_type === 'complete-loan') {
                $headers = array_merge($headers, [
                    'Total Returned', 'Expected Return', 'Return Variance'
                ]);
            }

            $headers[] = 'Commodities (name:allocated:collected)';
            fputcsv($file, $headers);

            foreach ($farmers as $f) {
                $commodities = collect($f['commodities'])->map(fn($c) =>
                    "{$c['name']}:{$c['allocated']}:{$c['collected']}"
                )->implode(' | ');

                $row = [
                    $f['farmer_id'],
                    $f['farmer_name'],
                    $f['registration_number'],
                    $f['payment_status'],
                    number_format($f['total_allocated'], 2),
                    number_format($f['total_collected'], 2),
                ];

                if ($season->loan_type === 'complete-loan') {
                    $row = array_merge($row, [
                        number_format($f['total_returned'] ?? 0, 2),
                        number_format($f['expected_return'] ?? 0, 2),
                        number_format($f['return_variance'] ?? 0, 2),
                    ]);
                }

                $row[] = $commodities;
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export tenant distribution as comprehensive PDF
     */
    protected function exportTenantDistributionPDF($allocationSummary, $reportData, $season, $tenant)
    {
        $filename = "tenant_distribution_{$tenant->id}_{$season->name}_" . date('Y-m-d') . ".pdf";

        // Debug: Check if data exists
        $hasAllocationData = !empty($allocationSummary['tenant_allocations']);
        $hasReportData = !empty($reportData) && isset($reportData['summary']);

        $html = "
        <html>
        <head>
            <title>Tenant Distribution Report</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .section { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .summary { background-color: #e8f4f8; padding: 15px; border-radius: 5px; border: 1px solid #bee3f8; }
                .summary p { margin: 5px 0; }
                .no-data { background-color: #fed7d7; padding: 15px; border-radius: 5px; border: 1px solid #fc8181; color: #c53030; }
                h1 { color: #2d3748; margin-bottom: 10px; }
                h2 { color: #4a5568; margin-bottom: 5px; }
                h3 { color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; }
                .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #718096; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Tenant Distribution Report</h1>
                <h2>{$season->name} - {$tenant->id}</h2>
                <p><strong>Season Type:</strong> " . ucfirst($season->type ?? 'Unknown') . " | <strong>Loan Type:</strong> " . ucfirst(str_replace('-', ' ', $season->loan_type ?? 'Unknown')) . "</p>
                <p><strong>Generated on:</strong> " . now()->toDateTimeString() . "</p>
            </div>";

        // Check if we have allocation summary data
        if ($hasAllocationData && !empty($allocationSummary['summary'])) {
            $html .= "
            <div class='section summary'>
                <h3>Allocation Summary</h3>
                <p><strong>Total Allocated:</strong> " . number_format($allocationSummary['summary']['total_allocated'] ?? 0) . " units</p>
                <p><strong>Total Distributed:</strong> " . number_format($allocationSummary['summary']['total_distributed'] ?? 0) . " units</p>
                <p><strong>Total Remaining:</strong> " . number_format($allocationSummary['summary']['total_remaining'] ?? 0) . " units</p>
                <p><strong>Distribution Percentage:</strong> " . ($allocationSummary['summary']['distribution_percentage'] ?? 0) . "%</p>
            </div>

            <div class='section'>
                <h3>Commodity Distribution</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Commodity</th>
                            <th>Unit</th>
                            <th>Original Allocated</th>
                            <th>Distributed</th>
                            <th>Remaining Stock</th>
                        </tr>
                    </thead>
                    <tbody>";

            $allocationCount = 0;
            foreach ($allocationSummary['tenant_allocations'] as $key => $allocation) {
                if (is_object($allocation) && isset($allocation->commodity_name)) {
                    $html .= "
                        <tr>
                            <td>{$allocation->commodity_name}</td>
                            <td>{$allocation->unit}</td>
                            <td>" . number_format($allocation->original_allocated ?? 0) . "</td>
                            <td>" . number_format($allocation->distributed ?? 0) . "</td>
                            <td>" . number_format($allocation->remaining_stock ?? 0) . "</td>
                        </tr>";
                    $allocationCount++;
                }
            }

            if ($allocationCount === 0) {
                $html .= "<tr><td colspan='5' class='no-data'>No allocation data found for this tenant and season.</td></tr>";
            }

            $html .= "
                    </tbody>
                </table>
            </div>";
        } else {
            $html .= "
            <div class='section no-data'>
                <h3>No Allocation Data Found</h3>
                <p>No allocation data was found for tenant '{$tenant->id}' in season '{$season->name}'.</p>
                <p>This could mean:</p>
                <ul>
                    <li>The tenant has not been allocated any commodities for this season</li>
                    <li>The season data is not properly synced to the tenant database</li>
                    <li>There was an error retrieving the allocation data</li>
                </ul>
            </div>";
        }

        // Farmers Summary Section
        $html .= "
            <div class='section'>
                <h3>Farmers Summary</h3>";

        if ($hasReportData) {
            $html .= "
                <p><strong>Total Farmers:</strong> " . ($reportData['summary']['total_farmers'] ?? 0) . "</p>
                <p><strong>Farmers Collected:</strong> " . ($reportData['summary']['farmers_collected'] ?? 0) . "</p>";

            if (($season->loan_type ?? '') === 'complete-loan') {
                $html .= "<p><strong>Farmers Returned:</strong> " . ($reportData['summary']['farmers_returned'] ?? 0) . "</p>";
            }
        } else {
            $html .= "<p class='no-data'>No farmer distribution data available.</p>";
        }

        $html .= "
            </div>";

        // Detailed Commodity Distribution
        if ($hasReportData && !empty($reportData['commodity_distribution'])) {
            $html .= "
            <div class='section'>
                <h3>Detailed Commodity Distribution</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Commodity</th>
                            <th>Unit</th>
                            <th>Original Allocated</th>
                            <th>Approved Qty</th>
                            <th>Distributed Qty</th>
                            <th>Collected Qty</th>
                            <th>Variance</th>
                            <th>Remaining Stock</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($reportData['commodity_distribution'] as $commodity) {
                $html .= "
                        <tr>
                            <td>" . ($commodity['commodity_name'] ?? 'N/A') . "</td>
                            <td>" . ($commodity['unit'] ?? 'N/A') . "</td>
                            <td>" . number_format($commodity['original_allocated'] ?? 0) . "</td>
                            <td>" . number_format($commodity['approved_quantity'] ?? 0) . "</td>
                            <td>" . number_format($commodity['distributed_quantity'] ?? 0) . "</td>
                            <td>" . number_format($commodity['collected_quantity'] ?? 0) . "</td>
                            <td>" . number_format($commodity['collection_variance'] ?? 0) . "</td>
                            <td>" . number_format($commodity['remaining_stock'] ?? 0) . "</td>
                        </tr>";
            }

            $html .= "
                    </tbody>
                </table>
            </div>";
        }

        $html .= "
            <div class='footer'>
                <p>This report was generated by the AFNON Management System</p>
                <p>Confidential - For internal use only</p>
            </div>
        </body>
        </html>";

        // Generate PDF using DomPDF
        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Get comprehensive tenant allocation summary
     */
    protected function getTenantAllocationSummary(GlobalSeason $season, Tenant $tenant): array
    {
        // Get allocations from global_tenant_allocations table
        $globalAllocations = \App\Models\GlobalTenantAllocation::with('commodity')
            ->where('global_season_id', $season->id)
            ->where('tenant_id', $tenant->id)
            ->get()
            ->keyBy('global_commodity_id');

        // Switch to tenant database to get current allocation status
        $this->setTenantConnection($tenant);

        $tenantSeason = \Illuminate\Support\Facades\DB::connection('tenant')
            ->table('seasons')
            ->where('uuid', $season->uuid)
            ->first();

        $processedAllocations = [];
        $totalAllocated = 0;
        $totalDistributed = 0;
        $totalRemaining = 0;
        $totalAvailableStock = 0;

        if ($tenantSeason) {
            // Get current allocation status from tenant DB
            $tenantAllocations = \Illuminate\Support\Facades\DB::connection('tenant')
                ->table('allocations')
                ->join('commodities', 'allocations.commodity_id', '=', 'commodities.id')
                ->where('allocations.season_id', $tenantSeason->id)
                ->select(
                    'commodities.id as commodity_id',
                    'commodities.name as commodity_name',
                    'commodities.unit',
                    'allocations.allocated_stock as remaining_stock'
                )
                ->get()
                ->keyBy('commodity_id');

            // Calculate distributed amounts (original allocated - remaining)
            foreach ($globalAllocations as $globalCommodityId => $globalAlloc) {
                $commodityName = $globalAlloc->commodity->name;
                $originalAllocated = $globalAlloc->allocated_stock;

                // Find matching tenant commodity
                $tenantCommodity = \Illuminate\Support\Facades\DB::connection('tenant')
                    ->table('commodities')
                    ->where('name', $commodityName)
                    ->first();

                if ($tenantCommodity) {
                    $remainingStock = $tenantAllocations->get($tenantCommodity->id)?->remaining_stock ?? 0;
                    $distributed = max(0, $originalAllocated - $remainingStock);

                    $processedAllocations[] = (object) [
                        'commodity_name' => $commodityName,
                        'unit' => $globalAlloc->commodity->unit,
                        'original_allocated' => $originalAllocated,
                        'distributed' => $distributed,
                        'remaining_stock' => $remainingStock,
                        'available_stock' => $remainingStock,
                    ];

                    $totalAllocated += $originalAllocated;
                    $totalDistributed += $distributed;
                    $totalRemaining += $remainingStock;
                    $totalAvailableStock += $remainingStock;
                }
            }
        }

        return [
            'global_allocations' => $globalAllocations,
            'tenant_allocations' => $processedAllocations,
            'summary' => [
                'total_allocated' => $totalAllocated,
                'total_distributed' => $totalDistributed,
                'total_remaining' => $totalRemaining,
                'total_available_stock' => $totalAvailableStock,
                'distribution_percentage' => $totalAllocated > 0 ? round(($totalDistributed / $totalAllocated) * 100, 1) : 0,
            ]
        ];
    }

    /**
     * Set up tenant database connection
     */
    protected function setTenantConnection(Tenant $tenant): void
    {
        $databaseName = $tenant->database()->getName();

        \Illuminate\Support\Facades\Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $databaseName,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        \Illuminate\Support\Facades\DB::purge('tenant');
        \Illuminate\Support\Facades\DB::reconnect('tenant');
    }
}
