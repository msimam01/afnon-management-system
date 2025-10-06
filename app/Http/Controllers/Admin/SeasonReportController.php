<?php

namespace App\Http\Controllers\Admin;

use App\Models\Season;
use App\Models\Application;
use App\Models\CollectionVerification;
use App\Models\MonetaryReturn;
use App\Models\CommodityMarketPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SeasonReportController extends Controller
{
    public function index()
    {
        $seasons = Season::withCount([
            'applications',
            'applications as approved_applications_count' => function ($query) {
                $query->where('status', 'approved');
            }
        ])->orderBy('created_at', 'desc')->get();

        return view('admin.reports.seasons.index', compact('seasons'));
    }

    public function show(Season $season)
    {
        // Basic season statistics
        $statistics = $this->getSeasonStatistics($season);

        // Collection insights
        $collectionInsights = $this->getCollectionInsights($season);

        // Financial insights
        $financialInsights = $this->getFinancialInsights($season);

        // Commodity insights
        $commodityInsights = $this->getCommodityInsights($season);

        // Farmer details
        $farmerDetails = $this->getFarmerDetails($season);

        return view('admin.reports.seasons.show', compact(
            'season',
            'statistics',
            'collectionInsights',
            'financialInsights',
            'commodityInsights',
            'farmerDetails'
        ));
    }

    private function getSeasonStatistics(Season $season)
    {
        $totalApplications = $season->applications()->count();
        $approvedApplications = $season->applications()->where('status', 'approved')->count();
        $collectedApplications = $season->applications()
            ->whereHas('collectionVerification')
            ->count();

        // All approved applications in this season follow the season's loan type
        $coFundedApplications = $season->loan_type === 'co-funded' ? $approvedApplications : 0;
        $completeLoanApplications = $season->loan_type === 'complete-loan' ? $approvedApplications : 0;

        return [
            'total_applications' => $totalApplications,
            'approved_applications' => $approvedApplications,
            'collected_applications' => $collectedApplications,
            'co_funded_applications' => $coFundedApplications,
            'complete_loan_applications' => $completeLoanApplications,
            'collection_rate' => $approvedApplications > 0 ? ($collectedApplications / $approvedApplications) * 100 : 0,
            'loan_type' => $season->loan_type,
        ];
    }

    private function getCollectionInsights(Season $season)
    {
        // Farmers who collected commodities
        $farmersCollected = DB::table('applications')
            ->join('collection_verifications', 'applications.id', '=', 'collection_verifications.application_id')
            ->join('farmers', 'applications.farmer_id', '=', 'farmers.id')
            ->where('applications.season_id', $season->id)
            ->where('collection_verifications.status', 'approved')
            ->select('farmers.id', 'farmers.full_name', 'farmers.registration_number')
            ->distinct()
            ->get();

        // Commodity collection summary
        $commodityCollections = DB::table('applications')
            ->join('collection_verifications', 'applications.id', '=', 'collection_verifications.application_id')
            ->join('commodity_allocations', 'applications.id', '=', 'commodity_allocations.application_id')
            ->where('applications.season_id', $season->id)
            ->where('collection_verifications.status', 'approved')
            ->select(
                'commodity_allocations.commodity_name',
                DB::raw('SUM(commodity_allocations.allocated_quantity) as total_collected'),
                DB::raw('COUNT(DISTINCT applications.id) as farmers_count')
            )
            ->groupBy('commodity_allocations.commodity_name')
            ->get();

        return [
            'farmers_collected' => $farmersCollected,
            'farmers_collected_count' => $farmersCollected->count(),
            'commodity_collections' => $commodityCollections,
        ];
    }

    private function getFinancialInsights(Season $season)
    {
        if ($season->loan_type === 'co-funded') {
            return $this->getCoFundedFinancialInsights($season);
        } else {
            return $this->getCompleteLoanFinancialInsights($season);
        }
    }

    private function getCoFundedFinancialInsights(Season $season)
    {
        // For co-funded: Farmers pay 50% upfront (disbursed amount) to collect commodities
        // Total loan amount for approved applications
        $totalLoanAmount = $season->applications()
            ->where('status', 'approved')
            ->sum('total_loan');

        // Total disbursed amount (50% of total loan that farmers receive)
        $totalDisbursed = $season->applications()
            ->where('status', 'approved')
            ->sum('disbursed_amount');

        // Expected payments (farmers must pay the disbursed amount to collect)
        $expectedPayments = $totalDisbursed;

        // Actual payments received
        $actualPayments = MonetaryReturn::whereHas('application', function($q) use ($season) {
                $q->where('season_id', $season->id);
            })
            ->where('status', 'paid')
            ->sum('amount');

        // Payment statistics
        $paidApplications = $season->applications()
            ->where('status', 'approved')
            ->where('payment_status', 'paid')
            ->count();

        $pendingApplications = $season->applications()
            ->where('status', 'approved')
            ->where('payment_status', 'pending')
            ->count();

        $approvedApplications = $season->applications()
            ->where('status', 'approved')
            ->count();

        return [
            'type' => 'co-funded',
            'total_loan_amount' => $totalLoanAmount,
            'total_disbursed' => $totalDisbursed,
            'expected_payments' => $expectedPayments,
            'actual_payments' => $actualPayments,
            'payment_rate' => $expectedPayments > 0 ? ($actualPayments / $expectedPayments) * 100 : 0,
            'paid_applications' => $paidApplications,
            'pending_applications' => $pendingApplications,
            'approved_applications' => $approvedApplications,
            'outstanding_amount' => $expectedPayments - $actualPayments,
            'equity_held' => $totalLoanAmount - $totalDisbursed, // 50% held by AFNEN
        ];
    }

    private function getCompleteLoanFinancialInsights(Season $season)
    {
        // For complete loan: No upfront payment required, farmers collect commodities and return equivalent value
        // Total loan amount for approved applications (full value provided as commodities)
        $totalLoanAmount = $season->applications()
            ->where('status', 'approved')
            ->sum('total_loan');

        // Applications that have collected commodities
        $collectedApplications = $season->applications()
            ->where('status', 'approved')
            ->whereHas('collectionVerification')
            ->count();

        // Applications with commodity returns
        $returnedApplications = $season->applications()
            ->where('status', 'approved')
            ->whereHas('returnVerification')
            ->count();

        $approvedApplications = $season->applications()
            ->where('status', 'approved')
            ->count();

        return [
            'type' => 'complete-loan',
            'total_loan_amount' => $totalLoanAmount,
            'approved_applications' => $approvedApplications,
            'collected_applications' => $collectedApplications,
            'returned_applications' => $returnedApplications,
            'pending_collections' => $approvedApplications - $collectedApplications,
            'pending_returns' => $collectedApplications - $returnedApplications,
            'collection_rate' => $approvedApplications > 0 ? ($collectedApplications / $approvedApplications) * 100 : 0,
            'return_rate' => $collectedApplications > 0 ? ($returnedApplications / $collectedApplications) * 100 : 0,
            'no_payment_required' => true, // Key difference from co-funded
        ];
    }

    private function getCommodityInsights(Season $season)
    {
        if ($season->loan_type === 'complete-loan') {
            return $this->getExpectedCommodityReturns($season);
        }

        // For co-funded seasons, no commodity returns expected
        return null;
    }

    private function getExpectedCommodityReturns(Season $season)
    {
        $expectedReturns = [];

        // This method should only be called for complete loan seasons
        // Only complete loan applications require commodity returns
        if ($season->loan_type !== 'complete-loan') {
            return [];
        }

        // Get all approved applications for this complete loan season
        // Only include applications that require commodity returns (complete loan type)
        // Complete loan applications should NOT have made any upfront payments
        $applications = $season->applications()
            ->with(['commodity_allocations', 'applicationCommodities.commodity', 'farmer'])
            ->where('status', 'approved')
            ->whereDoesntHave('monetaryReturn') // No monetary return record means no payment made
            ->get();

        foreach ($applications as $application) {
            // Only process applications that haven't made payments (true complete loan applications)
            // Find seed commodity from application commodities
            $seedCommodity = $application->applicationCommodities
                ->first(function ($item) {
                    return optional($item->commodity)->category === 'seed';
                });

            if ($seedCommodity && $seedCommodity->commodity) {
                // Get current market price for this seed commodity
                $marketPrice = CommodityMarketPrice::where('commodity_id', $seedCommodity->commodity->id)
                    ->where('season_id', $season->id)
                    ->first();

                $currentPrice = $marketPrice ? $marketPrice->current_price : $seedCommodity->commodity->price_per_unit;

                if ($currentPrice && $currentPrice > 0) {
                    $expectedQuantity = $application->total_loan / $currentPrice;

                    $commodityName = $seedCommodity->commodity->name;

                    if (!isset($expectedReturns[$commodityName])) {
                        $expectedReturns[$commodityName] = [
                            'commodity_name' => $commodityName,
                            'unit' => $seedCommodity->commodity->unit,
                            'current_price' => $currentPrice,
                            'total_expected_quantity' => 0,
                            'total_loan_value' => 0,
                            'farmers_count' => 0,
                            'applications' => []
                        ];
                    }

                    $expectedReturns[$commodityName]['total_expected_quantity'] += $expectedQuantity;
                    $expectedReturns[$commodityName]['total_loan_value'] += $application->total_loan;
                    $expectedReturns[$commodityName]['farmers_count']++;
                    $expectedReturns[$commodityName]['applications'][] = [
                        'farmer_name' => $application->farmer->full_name,
                        'reference_number' => $application->reference_number,
                        'total_loan' => $application->total_loan,
                        'expected_quantity' => $expectedQuantity,
                    ];
                }
            }
        }

        return array_values($expectedReturns);
    }

    private function getFarmerDetails(Season $season)
    {
        $applications = $season->applications()
            ->with([
                'farmer:id,full_name,phone,bvn,nin,registration_number',
                'monetaryReturn:id,amount,status,application_id',
                'collectionVerification:id,status,application_id',
                'commodity_allocations:id,application_id,commodity_name,allocated_quantity',
                'applicationCommodities.commodity:id,name,unit'
            ])
            ->where('status', 'approved')
            ->get();

        $farmerDetails = [];

        foreach ($applications as $application) {
            $farmer = $application->farmer;
            $monetaryReturn = $application->monetaryReturn;

            // Calculate total commodity allocated
            $totalCommodityAllocated = $application->commodity_allocations->sum('allocated_quantity');
            $commodityUnits = $application->applicationCommodities->pluck('commodity.unit')->unique()->implode(', ');

            // Calculate payment information based on loan type
            if ($season->loan_type === 'co-funded') {
                $totalLoan = $application->total_loan;
                $disbursedAmount = $application->disbursed_amount;
                $expectedPayment = $disbursedAmount;
                $actualPayment = $monetaryReturn ? $monetaryReturn->amount : 0;
                $paymentStatus = $application->payment_status;
                $outstandingAmount = $expectedPayment - $actualPayment;
            } else {
                // Complete loan - no upfront payment required
                $totalLoan = $application->total_loan;
                $disbursedAmount = $application->total_loan; // Full amount disbursed as commodities
                $expectedPayment = 0; // No monetary payment expected
                $actualPayment = 0;
                $paymentStatus = 'no_payment_required';
                $outstandingAmount = 0;
            }

            $farmerDetails[] = [
                'farmer_name' => $farmer->full_name,
                'phone' => $farmer->phone,
                'bvn' => $farmer->bvn,
                'nin' => $farmer->nin,
                'total_loan' => $totalLoan,
                'disbursed_amount' => $disbursedAmount,
                'total_commodity_allocated' => $totalCommodityAllocated,
                'commodity_units' => $commodityUnits,
                'application_reference' => $application->reference_number,
                'loan_type' => $season->loan_type,
                'payment_status' => $paymentStatus,
                'expected_payment' => $expectedPayment,
                'actual_payment' => $actualPayment,
                'outstanding_amount' => $outstandingAmount,
                'collection_status' => $application->collectionVerification ? $application->collectionVerification->status : 'pending',
                'payment_date' => $monetaryReturn ? $monetaryReturn->created_at : null,
            ];
        }

        return $farmerDetails;
    }

    public function exportPdf(Season $season)
    {
        $statistics = $this->getSeasonStatistics($season);
        $collectionInsights = $this->getCollectionInsights($season);
        $financialInsights = $this->getFinancialInsights($season);
        $commodityInsights = $this->getCommodityInsights($season);
        $farmerDetails = $this->getFarmerDetails($season);

        $pdf = Pdf::loadView('admin.reports.seasons.pdf', compact(
            'season',
            'statistics',
            'collectionInsights',
            'financialInsights',
            'commodityInsights',
            'farmerDetails'
        ));

        return $pdf->download('season-report-' . $season->slug . '-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Season $season)
    {
        $farmerDetails = $this->getFarmerDetails($season);

        return Excel::download(
            new \App\Exports\SeasonReportExport($season, $farmerDetails),
            'season-farmer-report-' . $season->slug . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
