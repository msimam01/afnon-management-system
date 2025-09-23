<?php

namespace App\Services;

use App\Models\Application;
use App\Models\CommodityMarketPrice;
use App\Models\Commodity;

class FarmerPaymentCalculationService
{
    /**
     * Calculate the total payment amount based on allocated commodities with current market prices
     */
    public static function calculateTotalPaymentAmount($application): array
    {
        $totalPaymentAmount = 0;
        $commodityBreakdown = [];

        // New rule: Always charge the disbursed amount when present
        $disbursedAmount = $application->disbursed_amount ?? 0;

        if ($disbursedAmount > 0) {
            return [
                'total_amount' => $disbursedAmount,
                'breakdown' => [],
                'calculation_method' => 'disbursed_amount',
                'message' => 'Payment charged as disbursed amount'
            ];
        }

        // If no disbursed amount available, fall back to allocations-based calculation
        $allocations = $application->commodity_allocations ?? collect([]);
        if ($allocations->isEmpty()) {
            return [
                'total_amount' => 0,
                'breakdown' => [],
                'calculation_method' => 'no_amount_available',
                'message' => 'No disbursed amount and no allocations available'
            ];
        }

        foreach ($allocations as $allocation) {
            $commodity = \App\Models\Commodity::where('name', $allocation->commodity_name)->first();
            $commodityId = $commodity ? $commodity->id : null;
            $currentPrice = self::getCurrentCommodityPrice($commodityId, $application->season_id);
            $allocatedQuantity = $allocation->allocated_quantity ?? 0;
            $totalValue = $allocatedQuantity * $currentPrice;
            $totalPaymentAmount += $totalValue;

            $commodityBreakdown[] = [
                'commodity_name' => $allocation->commodity_name,
                'allocated_quantity' => $allocatedQuantity,
                'unit_price' => $currentPrice,
                'total_value' => $totalValue,
                'price_source' => self::getPriceSource($commodityId, $application->season_id)
            ];
        }

        return [
            'total_amount' => $totalPaymentAmount,
            'breakdown' => $commodityBreakdown,
            'calculation_method' => 'commodity_allocations_fallback',
            'message' => 'No disbursed amount; using allocations-based calculation'
        ];
    }

    /**
     * Get current commodity price from market price table first, fallback to commodity table
     */
    private static function getCurrentCommodityPrice($commodityId, $seasonId)
    {
        // First try to get price from commodity market price table
        $marketPrice = CommodityMarketPrice::where('commodity_id', $commodityId)
            ->where('season_id', $seasonId)
            ->first();

        if ($marketPrice && $marketPrice->current_price) {
            return $marketPrice->current_price;
        }

        // Fallback to commodity table price
        $commodity = Commodity::find($commodityId);
        return $commodity ? $commodity->price_per_unit : 0;
    }

    /**
     * Get the source of the price (market price or commodity table)
     */
    private static function getPriceSource($commodityId, $seasonId): string
    {
        $marketPrice = CommodityMarketPrice::where('commodity_id', $commodityId)
            ->where('season_id', $seasonId)
            ->first();

        return $marketPrice && $marketPrice->current_price ? 'market_price' : 'commodity_table';
    }

    /**
     * Validate payment calculation and return any issues
     */
    public static function validatePaymentCalculation(Application $application): array
    {
        $issues = [];
        $calculation = self::calculateTotalPaymentAmount($application);

        // Check if any commodity has zero price
        foreach ($calculation['breakdown'] as $commodity) {
            if ($commodity['unit_price'] <= 0) {
                $issues[] = "Commodity '{$commodity['commodity_name']}' has no valid price set";
            }
        }

        // Check if total amount is zero
        if ($calculation['total_amount'] <= 0) {
            $issues[] = "Total payment amount is zero - no commodities allocated or all prices are zero";
        }

        // Check if using fallback method
        if ($calculation['calculation_method'] === 'disbursed_amount_fallback') {
            $issues[] = "Using disbursed amount fallback - commodity allocations not found";
        }

        return [
            'is_valid' => empty($issues),
            'issues' => $issues,
            'calculation' => $calculation
        ];
    }
}
