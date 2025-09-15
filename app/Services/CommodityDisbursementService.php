<?php

namespace App\Services;

use App\Models\Application;
use App\Models\CommodityAllocation;

class CommodityDisbursementService
{
    /**
     * Calculate proportional commodity disbursement based on loan percentage
     */
    public static function calculateProportionalDisbursement(Application $application, float $disbursementPercentage = null): array
    {
        // If no percentage provided, use the current disbursement amount
        if ($disbursementPercentage === null) {
            // Calculate disbursement percentage based on total loan (including insurance)
            $totalLoan = $application->total_loan ?? 0;
            $disbursedAmount = $application->disbursed_amount ?? 0;

            $disbursementPercentage = $totalLoan > 0
                ? ($disbursedAmount / $totalLoan) * 100
                : 100;
        }

        $disbursementRatio = $disbursementPercentage / 100;
        $proportionalCommodities = [];

        // Use application commodities (same as main commodity breakdown table)
        foreach ($application->commodities as $commodity) {
            $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
            $farmSize = $application->farm->size ?? 0;
            $originalQuantity = $qtyPerHectare * $farmSize; // Same calculation as main table
            $unitPrice = $commodity->price_per_unit ?? 0;
            $originalValue = $originalQuantity * $unitPrice;

            $proportionalCommodities[] = [
                'commodity_id' => $commodity->id,
                'commodity_name' => $commodity->name,
                'unit' => $commodity->unit,
                'original_quantity' => $originalQuantity,
                'disbursed_quantity' => round($originalQuantity * $disbursementRatio, 2),
                'unit_price' => $unitPrice,
                'original_value' => $originalValue,
                'disbursed_value' => round($originalValue * $disbursementRatio, 2),
            ];
        }

        // Calculate insurance proportional disbursement
        $originalInsuranceAmount = $application->insurance_amount ?? 0;
        $disbursedInsuranceAmount = round($originalInsuranceAmount * $disbursementRatio, 2);

        // Add insurance as a "commodity" in the breakdown
        if ($originalInsuranceAmount > 0) {
            $proportionalCommodities[] = [
                'commodity_id' => 'insurance',
                'commodity_name' => 'Insurance Premium',
                'unit' => 'amount',
                'original_quantity' => 1,
                'disbursed_quantity' => 1,
                'unit_price' => $originalInsuranceAmount,
                'original_value' => $originalInsuranceAmount,
                'disbursed_value' => $disbursedInsuranceAmount,
            ];
        }

        return [
            'disbursement_percentage' => $disbursementPercentage,
            'disbursement_ratio' => $disbursementRatio,
            'commodities' => $proportionalCommodities,
            'total_original_value' => array_sum(array_column($proportionalCommodities, 'original_value')),
            'total_disbursed_value' => array_sum(array_column($proportionalCommodities, 'disbursed_value')),
            'original_insurance_amount' => $originalInsuranceAmount,
            'disbursed_insurance_amount' => $disbursedInsuranceAmount,
        ];
    }

    /**
     * Get disbursement summary for display
     */
    public static function getDisbursementSummary(Application $application): array
    {
        $disbursement = self::calculateProportionalDisbursement($application);

        return [
            'monetary_disbursement' => $application->disbursed_amount,
            'commodity_disbursement' => $disbursement,
            'total_loan' => $application->total_loan,
            'disbursement_percentage' => $disbursement['disbursement_percentage'],
        ];
    }
}
