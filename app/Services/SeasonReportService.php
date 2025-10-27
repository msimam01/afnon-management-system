<?php

namespace App\Services;

use App\Models\Season;
use Illuminate\Support\Collection;

class SeasonReportService
{
    protected $season;

    public function __construct(Season $season)
    {
        $this->season = $season->load([
            'applications' => function($query) {
                $query->with([
                    'farmer',
                    'commodity_allocations.commodity',
                    'collectionVerification',
                    'returnVerification',
                    'collectionVerification.commodity',
                    'returnVerification.commodity'
                ]);
            }
        ]);
    }

    public function generateReportData(): Collection
    {
        $applications = $this->season->applications->load([
            'farmer',
            'commodity_allocations.commodity',
            'collectionVerification',
            'returnVerification'
        ]);
        
        // Log the first application for debugging
        if ($applications->isNotEmpty()) {
            \Log::debug('First application data:', [
                'farmer' => $applications->first()->farmer ? $applications->first()->farmer->toArray() : null,
                'commodity_allocations' => $applications->first()->commodity_allocations->toArray(),
                'collection_verification' => $applications->first()->collectionVerification ? $applications->first()->collectionVerification->toArray() : null,
                'return_verification' => $applications->first()->returnVerification ? $applications->first()->returnVerification->toArray() : null
            ]);
        }
        
        return $applications->map(function ($application) {
            $collection = $application->collectionVerification;
            $return = $application->returnVerification;
            
            // Get all commodity allocations with their details
            $commodityData = $application->commodity_allocations->map(function ($allocation) use ($collection, $return) {
                // Find collection verification for this specific commodity
                $collectionQty = 0;
                if ($collection) {
                    if ($collection->commodity_id === $allocation->commodity_id) {
                        $collectionQty = $collection->collected_quantity;
                    } elseif ($collection->application_id === $allocation->application_id) {
                        // If we have a collection for this application but not this specific commodity,
                        // we'll still show it for the first commodity to indicate something was collected
                        static $hasShownCollection = false;
                        if (!$hasShownCollection) {
                            $collectionQty = $collection->collected_quantity;
                            $hasShownCollection = true;
                        }
                    }
                }
                
                // Find return verification for this specific commodity
                $returnQty = $return && $return->commodity_id === $allocation->commodity_id
                    ? $return->returned_quantity
                    : 0;
                
                $expectedQty = $return && $return->commodity_id === $allocation->commodity_id
                    ? $return->expected_quantity
                    : 0;
                
                return [
                    'id' => $allocation->commodity_id,
                    'name' => $allocation->commodity_name,
                    'unit' => $allocation->commodity->unit ?? 'units',
                    'allocated' => $allocation->allocated_quantity,
                    'collected' => $collectionQty,
                    'expected' => $expectedQty,
                    'returned' => $returnQty,
                    'variance' => $expectedQty - $returnQty,
                    'unit_price' => $allocation->unit_price,
                    'total_value' => $allocation->total_value
                ];
            });
            
            return [
                'farmer_name' => $application->farmer->full_name ?? 'N/A',
                'farmer_phone' => $application->farmer->phone ?? 'N/A',
                'registration_number' => $application->farmer->registration_number ?? 'N/A',
                'commodities' => $commodityData->toArray(),
                'total_allocated_value' => $commodityData->sum('total_value'),
                'total_allocated_qty' => $commodityData->sum('allocated'),
                'total_collected_qty' => $commodityData->sum('collected'),
                'total_expected_qty' => $commodityData->sum('expected'),
                'total_returned_qty' => $commodityData->sum('returned'),
                'total_variance' => $commodityData->sum('variance'),
                'status' => $application->status,
                'application_date' => $application->created_at->format('Y-m-d'),
                'collection_date' => $collection ? $collection->created_at->format('Y-m-d') : 'N/A',
                'return_date' => $return ? $return->created_at->format('Y-m-d') : 'N/A',
                'shortfall_reason' => $return->shortfall_reason ?? 'N/A',
            ];
        });
    }

    public function getSummary(): array
    {
        // Eager load all necessary relationships
        $applications = $this->season->applications->load([
            'commodity_allocations.commodity',
            'collectionVerification',
            'returnVerification',
            'farmer'
        ]);
        
        $totalFarmers = $applications->count();
        
        // Initialize summary arrays
        $totalAllocatedValue = 0;
        $totalAllocatedQty = 0;
        $totalCollected = 0;
        $totalExpected = 0;
        $totalReturned = 0;
        $commoditySummary = [];
        
        // Get all allocations for the season
        $allocations = $this->season->allocations()->with('commodity')->get();
        
        // Initialize commodity summary with all allocated commodities
        foreach ($allocations as $allocation) {
            $commodityId = $allocation->commodity_id;
            if (!isset($commoditySummary[$commodityId])) {
                $commoditySummary[$commodityId] = [
                    'id' => $commodityId,
                    'name' => $allocation->commodity->name,
                    'unit' => $allocation->commodity->unit ?? 'units',
                    'total_allocated' => 0,
                    'total_distributed' => 0,
                    'total_collected' => 0,
                    'total_expected' => 0,
                    'total_returned' => 0,
                    'variance' => 0,
                    'completion_rate' => 0
                ];
            }
            $commoditySummary[$commodityId]['total_allocated'] += $allocation->allocated_stock;
        }

        // Process each application's commodity allocations
        foreach ($applications as $app) {
            foreach ($app->commodity_allocations as $allocation) {
                $commodityId = $allocation->commodity_id;
                $allocatedQty = $allocation->allocated_quantity;
                
                // Initialize commodity in summary if not exists (shouldn't happen but just in case)
                if (!isset($commoditySummary[$commodityId])) {
                    $commoditySummary[$commodityId] = [
                        'id' => $commodityId,
                        'name' => $allocation->commodity_name,
                        'unit' => $allocation->commodity->unit ?? 'units',
                        'total_allocated' => 0,
                        'total_distributed' => 0,
                        'total_collected' => 0,
                        'total_expected' => 0,
                        'total_returned' => 0,
                        'variance' => 0,
                        'completion_rate' => 0
                    ];
                }
                
                // Update distributed quantities
                $commoditySummary[$commodityId]['total_distributed'] += $allocatedQty;
                $totalAllocatedQty += $allocatedQty;
                $totalAllocatedValue += $allocation->total_value;
                
                // Process collection verification
                if ($app->collectionVerification && $app->collectionVerification->commodity_id === $commodityId) {
                    $collected = $app->collectionVerification->collected_quantity ?? 0;
                    $commoditySummary[$commodityId]['total_collected'] += $collected;
                    $totalCollected += $collected;
                }
                
                // Process return verification
                if ($app->returnVerification && $app->returnVerification->commodity_id === $commodityId) {
                    $expected = $app->returnVerification->expected_quantity ?? 0;
                    $returned = $app->returnVerification->returned_quantity ?? 0;
                    
                    $commoditySummary[$commodityId]['total_expected'] += $expected;
                    $commoditySummary[$commodityId]['total_returned'] += $returned;
                    $commoditySummary[$commodityId]['variance'] = 
                        $commoditySummary[$commodityId]['total_expected'] - 
                        $commoditySummary[$commodityId]['total_returned'];
                    
                    $totalExpected += $expected;
                    $totalReturned += $returned;
                }
            }
        }
        
        // Calculate completion rates
        foreach ($commoditySummary as &$commodity) {
            if ($commodity['total_expected'] > 0) {
                $commodity['completion_rate'] = 
                    round(($commodity['total_returned'] / $commodity['total_expected']) * 100, 2);
            }
        }

        $completedApplications = $applications->filter(function ($app) {
            return $app->status === 'completed' || 
                  ($app->returnVerification && $app->returnVerification->status === 'completed');
        })->count();

        return [
            'season_name' => $this->season->name,
            'start_date' => $this->season->start_date,
            'end_date' => $this->season->end_date,
            'total_farmers' => $totalFarmers,
            'total_allocated_value' => $totalAllocatedValue,
            'total_allocated_qty' => $totalAllocatedQty,
            'total_collected' => $totalCollected,
            'total_expected' => $totalExpected,
            'total_returned' => $totalReturned,
            'total_variance' => $totalExpected - $totalReturned,
            'completion_rate' => $totalFarmers > 0 ? round(($completedApplications / $totalFarmers) * 100, 2) : 0,
            'commodity_summary' => array_values($commoditySummary),
            'is_complete_loan' => $this->season->loan_type === 'complete-loan',
        ];
    }
}
