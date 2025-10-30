<?php

namespace App\Services;

use App\Models\Season;
use App\Models\Application;
use App\Models\CollectionVerification;
use App\Models\ReturnVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeasonReportService
{
    protected $season;

    public function __construct(Season $season)
    {
        $this->season = $season;
    }

    /**
     * Generate comprehensive report data for the season
     */
    public function generateReportData(): array
    {
        $applications = Application::with([
            'farmer:id,full_name,phone,registration_number',
            'farm:id,size,location',
            'commodity_allocations',
            'applicationCommodities.commodity:id,name,category,unit'
        ])
        ->where('season_id', $this->season->id)
        ->where('status', 'approved')
        ->get();

        $reportData = [];

        foreach ($applications as $application) {
            $farmerData = [
                'farmer_id' => $application->farmer->id,
                'farmer_name' => $application->farmer->full_name,
                'farmer_phone' => $application->farmer->phone ?? 'N/A',
                'registration_number' => $application->farmer->registration_number ?? 'N/A',
                'reference_number' => $application->reference_number,
                'status' => $application->status,
                'application_date' => $application->created_at->format('Y-m-d'),
                'farm_size' => $application->farm->size ?? 0,
                'total_loan' => $application->total_loan ?? 0,
                'disbursed_amount' => $application->disbursed_amount ?? 0,
                'equity' => $application->equity ?? 0,
                'insurance_amount' => $application->insurance_amount ?? 0,
                'commodities' => [],
                'collection_date' => null,
                'return_date' => null,
                'shortfall_reason' => 'N/A',
            ];

            // Process commodity allocations and collections
            $totalAllocatedValue = 0;
            $totalAllocatedQty = 0;
            $totalCollectedQty = 0;
            $totalExpectedQty = 0;
            $totalReturnedQty = 0;
            $totalVariance = 0;

            foreach ($application->commodity_allocations as $allocation) {
                // Get commodity details
                $commodity = $application->applicationCommodities
                    ->where('commodity.name', $allocation->commodity_name)
                    ->first();

                if (!$commodity) {
                    continue;
                }

                // Get collection data
                $collectionVerification = CollectionVerification::where('application_id', $application->id)
                    ->where('commodity_id', $commodity->commodity_id)
                    ->first();

                $collectedQty = $collectionVerification->collected_quantity ?? 0;

                if ($collectionVerification && !$farmerData['collection_date']) {
                    $farmerData['collection_date'] = $collectionVerification->created_at->format('Y-m-d');
                }

                // Initialize return data
                $expectedQty = 0;
                $returnedQty = 0;
                $variance = 0;

                // For complete-loan seasons, get return data (only for seed commodities)
                // Return verification is per application, not per commodity
                if ($this->season->loan_type === 'complete-loan' && $commodity->commodity->category === 'seed') {
                    // Check if we already fetched the return verification
                    if (!isset($farmerData['_return_verification_fetched'])) {
                        $returnVerification = ReturnVerification::where('application_id', $application->id)->first();

                        if ($returnVerification) {
                            $expectedQty = $returnVerification->expected_quantity ?? 0;
                            $returnedQty = $returnVerification->returned_quantity ?? 0;
                            $variance = $returnVerification->variance ?? 0;

                            if (!$farmerData['return_date']) {
                                $farmerData['return_date'] = $returnVerification->created_at->format('Y-m-d');
                            }

                            if ($returnVerification->shortfall_reason) {
                                $farmerData['shortfall_reason'] = $returnVerification->shortfall_reason;
                            }

                            // Store the return data to avoid refetching
                            $farmerData['_return_verification'] = [
                                'expected' => $expectedQty,
                                'returned' => $returnedQty,
                                'variance' => $variance,
                            ];
                        }

                        $farmerData['_return_verification_fetched'] = true;
                    }

                    // Use the cached return data if available
                    if (isset($farmerData['_return_verification'])) {
                        $expectedQty = $farmerData['_return_verification']['expected'];
                        $returnedQty = $farmerData['_return_verification']['returned'];
                        $variance = $farmerData['_return_verification']['variance'];

                        $totalExpectedQty += $expectedQty;
                        $totalReturnedQty += $returnedQty;
                        $totalVariance += $variance;
                    }
                }

                $commodityData = [
                    'name' => $allocation->commodity_name,
                    'unit' => $commodity->commodity->unit ?? 'N/A',
                    'allocated' => $allocation->allocated_quantity,
                    'collected' => $collectedQty,
                    'expected' => $expectedQty,
                    'returned' => $returnedQty,
                    'variance' => $variance,
                    'unit_price' => $allocation->unit_price,
                    'total_value' => $allocation->total_value,
                ];

                $farmerData['commodities'][] = $commodityData;

                $totalAllocatedValue += $allocation->total_value;
                $totalAllocatedQty += $allocation->allocated_quantity;
                $totalCollectedQty += $collectedQty;
            }

            $farmerData['total_allocated_value'] = $totalAllocatedValue;
            $farmerData['total_allocated_qty'] = $totalAllocatedQty;
            $farmerData['total_collected_qty'] = $totalCollectedQty;
            $farmerData['total_expected_qty'] = $totalExpectedQty;
            $farmerData['total_returned_qty'] = $totalReturnedQty;
            $farmerData['total_variance'] = $totalVariance;

            // Clean up internal flags
            unset($farmerData['_return_verification_fetched']);
            unset($farmerData['_return_verification']);

            $reportData[] = $farmerData;
        }

        return $reportData;
    }

    /**
     * Generate summary statistics for the season
     */
    public function getSummary(): array
    {
        $reportData = $this->generateReportData();

        $summary = [
            'season_name' => $this->season->name,
            'season_type' => $this->season->type,
            'loan_type' => $this->season->loan_type,
            'is_complete_loan' => $this->season->loan_type === 'complete-loan',
            'start_date' => $this->season->start_date,
            'end_date' => $this->season->end_date,
            'insurance_rate' => $this->season->insurance_rate,
            'total_farmers' => count($reportData),
            'total_allocated_value' => 0,
            'total_allocated_qty' => 0,
            'total_collected' => 0,
            'total_expected' => 0,
            'total_returned' => 0,
            'total_variance' => 0,
            'total_disbursed' => 0,
            'total_equity' => 0,
            'total_insurance' => 0,
            'commodity_summary' => [],
        ];

        // Aggregate commodity-level data
        $commodities = [];

        foreach ($reportData as $farmer) {
            $summary['total_allocated_value'] += $farmer['total_allocated_value'];
            $summary['total_allocated_qty'] += $farmer['total_allocated_qty'];
            $summary['total_collected'] += $farmer['total_collected_qty'];
            $summary['total_expected'] += $farmer['total_expected_qty'];
            $summary['total_returned'] += $farmer['total_returned_qty'];
            $summary['total_variance'] += $farmer['total_variance'];
            $summary['total_disbursed'] += $farmer['disbursed_amount'];
            $summary['total_equity'] += $farmer['equity'];
            $summary['total_insurance'] += $farmer['insurance_amount'];

            foreach ($farmer['commodities'] as $commodity) {
                $name = $commodity['name'];

                if (!isset($commodities[$name])) {
                    $commodities[$name] = [
                        'name' => $name,
                        'unit' => $commodity['unit'],
                        'total_allocated' => 0,
                        'total_distributed' => 0,
                        'total_collected' => 0,
                        'total_expected' => 0,
                        'total_returned' => 0,
                        'variance' => 0,
                        'total_value' => 0,
                    ];
                }

                $commodities[$name]['total_allocated'] += $commodity['allocated'];
                $commodities[$name]['total_distributed'] += $commodity['allocated'];
                $commodities[$name]['total_collected'] += $commodity['collected'];
                $commodities[$name]['total_expected'] += $commodity['expected'];
                $commodities[$name]['total_returned'] += $commodity['returned'];
                $commodities[$name]['variance'] += $commodity['variance'];
                $commodities[$name]['total_value'] += $commodity['total_value'];
            }
        }

        // Calculate completion rates per commodity
        foreach ($commodities as $name => &$commodity) {
            $commodity['collection_rate'] = $commodity['total_distributed'] > 0
                ? round(($commodity['total_collected'] / $commodity['total_distributed']) * 100, 2)
                : 0;

            if ($this->season->loan_type === 'complete-loan') {
                $commodity['completion_rate'] = $commodity['total_expected'] > 0
                    ? round(($commodity['total_returned'] / $commodity['total_expected']) * 100, 2)
                    : 0;
            }
        }

        $summary['commodity_summary'] = array_values($commodities);

        // Calculate overall completion rate
        if ($this->season->loan_type === 'complete-loan') {
            $summary['completion_rate'] = $summary['total_expected'] > 0
                ? round(($summary['total_returned'] / $summary['total_expected']) * 100, 2)
                : 0;
        } else {
            $summary['completion_rate'] = $summary['total_allocated_qty'] > 0
                ? round(($summary['total_collected'] / $summary['total_allocated_qty']) * 100, 2)
                : 0;
        }

        // Financial summary
        $summary['expected_payments'] = $summary['total_disbursed'];
        $summary['collection_rate'] = $summary['total_allocated_qty'] > 0
            ? round(($summary['total_collected'] / $summary['total_allocated_qty']) * 100, 2)
            : 0;

        return $summary;
    }

    /**
     * Get detailed commodity breakdown
     */
    public function getCommodityBreakdown(): array
    {
        $summary = $this->getSummary();
        return $summary['commodity_summary'];
    }

    /**
     * Get farmers with shortfalls (for complete-loan seasons)
     */
    public function getFarmersWithShortfalls(): array
    {
        if ($this->season->loan_type !== 'complete-loan') {
            return [];
        }

        $reportData = $this->generateReportData();

        return array_filter($reportData, function ($farmer) {
            return $farmer['total_variance'] > 0 && $farmer['shortfall_reason'] !== 'N/A';
        });
    }

    /**
     * Get farmers with pending collections
     */
    public function getFarmersWithPendingCollections(): array
    {
        $reportData = $this->generateReportData();

        return array_filter($reportData, function ($farmer) {
            return $farmer['total_collected_qty'] === 0 || $farmer['collection_date'] === null;
        });
    }

    /**
     * Get farmers with pending returns (for complete-loan seasons)
     */
    public function getFarmersWithPendingReturns(): array
    {
        if ($this->season->loan_type !== 'complete-loan') {
            return [];
        }

        $reportData = $this->generateReportData();

        return array_filter($reportData, function ($farmer) {
            return $farmer['total_expected_qty'] > 0 && $farmer['return_date'] === null;
        });
    }

    /**
     * Export data in a format suitable for CSV/Excel
     */
    public function getExportData(): array
    {
        $reportData = $this->generateReportData();
        $exportData = [];

        foreach ($reportData as $farmer) {
            $baseRow = [
                'Farmer Name' => $farmer['farmer_name'],
                'Registration Number' => $farmer['registration_number'],
                'Phone' => $farmer['farmer_phone'],
                'Reference Number' => $farmer['reference_number'],
                'Application Date' => $farmer['application_date'],
                'Farm Size (Ha)' => $farmer['farm_size'],
                'Total Loan' => $farmer['total_loan'],
                'Disbursed Amount' => $farmer['disbursed_amount'],
                'Equity' => $farmer['equity'],
                'Insurance' => $farmer['insurance_amount'],
            ];

            foreach ($farmer['commodities'] as $commodity) {
                $row = array_merge($baseRow, [
                    'Commodity' => $commodity['name'],
                    'Unit' => $commodity['unit'],
                    'Allocated Quantity' => $commodity['allocated'],
                    'Collected Quantity' => $commodity['collected'],
                    'Unit Price' => $commodity['unit_price'],
                    'Total Value' => $commodity['total_value'],
                ]);

                if ($this->season->loan_type === 'complete-loan') {
                    $row['Expected Return'] = $commodity['expected'];
                    $row['Returned Quantity'] = $commodity['returned'];
                    $row['Variance'] = $commodity['variance'];
                }

                $row['Collection Date'] = $farmer['collection_date'] ?? 'N/A';

                if ($this->season->loan_type === 'complete-loan') {
                    $row['Return Date'] = $farmer['return_date'] ?? 'N/A';
                    $row['Shortfall Reason'] = $farmer['shortfall_reason'];
                }

                $exportData[] = $row;
            }
        }

        return $exportData;
    }
}
