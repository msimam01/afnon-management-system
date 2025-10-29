<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Generate Season Allocation Report.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function seasonAllocationReport(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'season_uuid' => 'required|string|exists:global_seasons,uuid'
            ]);

            $reportData = $this->reportService->generateSeasonAllocationReport($request->season_uuid);

            if (!$reportData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Season not found or invalid data'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $reportData,
                'chart_config' => $this->getSeasonAllocationChartConfig($reportData)
            ]);

        } catch (\Exception $e) {
            Log::error("Error generating season allocation report: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report'
            ], 500);
        }
    }

    /**
     * Generate Tenant Distribution Report.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function tenantDistributionReport(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'season_uuid' => 'required|string|exists:global_seasons,uuid',
                'tenant_id' => 'required|string|exists:tenants,id'
            ]);

            $reportData = $this->reportService->generateTenantDistributionReport(
                $request->season_uuid,
                $request->tenant_id
            );

            if (!$reportData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Season or tenant not found or invalid data'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);

        } catch (\Exception $e) {
            Log::error("Error generating tenant distribution report: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report'
            ], 500);
        }
    }

    /**
     * Generate Return Compliance Report.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function returnComplianceReport(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'season_uuid' => 'required|string|exists:global_seasons,uuid'
            ]);

            $reportData = $this->reportService->generateReturnComplianceReport($request->season_uuid);

            if (!$reportData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Season not found or invalid data'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $reportData
            ]);

        } catch (\Exception $e) {
            Log::error("Error generating return compliance report: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report'
            ], 500);
        }
    }

    /**
     * Generate Chart.js configuration for Season Allocation Report.
     *
     * @param array $reportData
     * @return array
     */
    protected function getSeasonAllocationChartConfig(array $reportData): array
    {
        $labels = array_column($reportData['commodities'], 'name');
        $allocated = array_column($reportData['commodities'], 'allocated');
        $remaining = array_column($reportData['commodities'], 'remaining');
        $totalStock = array_column($reportData['commodities'], 'total_stock');

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Allocated Stock',
                        'data' => $allocated,
                        'backgroundColor' => 'rgba(75, 192, 192, 0.8)',
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Remaining Stock',
                        'data' => $remaining,
                        'backgroundColor' => 'rgba(255, 206, 86, 0.8)',
                        'borderColor' => 'rgba(255, 206, 86, 1)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Total Stock',
                        'data' => $totalStock,
                        'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ],
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => "Season Allocation Report - {$reportData['season']}"
                    ],
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) {
                                return context.dataset.label + ": " + context.raw;
                            }'
                        ]
                    ]
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'title' => [
                            'display' => true,
                            'text' => 'Quantity'
                        ]
                    ],
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Commodities'
                        ]
                    ]
                ]
            ]
        ];
    }
}
