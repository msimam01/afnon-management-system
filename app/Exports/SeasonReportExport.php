<?php

namespace App\Exports;

use App\Models\Season;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class SeasonReportExport implements FromCollection, WithHeadings, WithTitle
{
    protected $season;
    protected $farmerDetails;

    public function __construct(Season $season, $farmerDetails = null)
    {
        $this->season = $season;
        $this->farmerDetails = $farmerDetails;
    }

    public function collection()
    {
        $data = collect();

        if (!$this->farmerDetails || !is_array($this->farmerDetails) || count($this->farmerDetails) === 0) {
            $data->push([
                'No Data',
                'No Data',
                'No Data',
                'No Data',
                '0.00',
                '0.00',
                '0 units'
            ]);
            return $data;
        }

        foreach ($this->farmerDetails as $farmer) {
            $data->push([
                $farmer['farmer_name'] ?? 'No Name',
                $farmer['phone'] ?? 'No Phone',
                $farmer['bvn'] ?: 'N/A',
                $farmer['nin'] ?: 'N/A',
                isset($farmer['total_loan']) ? number_format($farmer['total_loan'], 2) : '0.00',
                isset($farmer['disbursed_amount']) ? number_format($farmer['disbursed_amount'], 2) : '0.00',
                isset($farmer['total_commodity_allocated']) ? number_format($farmer['total_commodity_allocated'], 2) . ' ' . ($farmer['commodity_units'] ?: 'units') : '0 units'
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Farmer Name',
            'Phone',
            'BVN',
            'NIN',
            'Total Loan (₦)',
            'Disbursed Amount (₦)',
            'Total Commodity Allocated'
        ];
    }

    public function title(): string
    {
        return $this->season->name . ' - Farmer Details';
    }
}

class SeasonOverviewSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $season;
    protected $statistics;

    public function __construct(Season $season, $statistics)
    {
        $this->season = $season;
        $this->statistics = $statistics;
    }

    public function collection()
    {
        return collect([
            [
                'Season Name' => $this->season->name,
                'Season Type' => ucfirst($this->season->type),
                'Loan Type' => ucfirst(str_replace('-', ' ', $this->season->loan_type)),
                'Status' => ucfirst($this->season->status),
                'Start Date' => $this->season->start_date,
                'End Date' => $this->season->end_date,
                'Collection Start' => $this->season->collection_start_date,
                'Collection End' => $this->season->collection_end_date,
                'Return Deadline' => $this->season->return_deadline ?? 'N/A',
                'Budget' => $this->season->budget,
                'Insurance Rate' => $this->season->insurance_rate . '%',
            ],
            [], // Empty row
            [
                'Metric' => 'Total Applications',
                'Value' => $this->statistics['total_applications'],
            ],
            [
                'Metric' => 'Approved Applications',
                'Value' => $this->statistics['approved_applications'],
            ],
            [
                'Metric' => 'Collected Applications',
                'Value' => $this->statistics['collected_applications'],
            ],
            [
                'Metric' => 'Collection Rate',
                'Value' => number_format($this->statistics['collection_rate'], 2) . '%',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'Season Information',
            'Value',
        ];
    }

    public function title(): string
    {
        return 'Season Overview';
    }
}

class CommodityCollectionsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $collectionInsights;

    public function __construct($collectionInsights)
    {
        $this->collectionInsights = $collectionInsights;
    }

    public function collection()
    {
        $data = collect();

        // Add farmers collected summary
        $data->push([
            'Summary',
            'Farmers Collected Count: ' . $this->collectionInsights['farmers_collected_count'],
        ]);
        $data->push([]); // Empty row

        // Add commodity collections header
        $data->push([
            'Commodity Name',
            'Total Collected (Bags)',
            'Number of Farmers',
        ]);

        // Add commodity collections data
        foreach ($this->collectionInsights['commodity_collections'] as $commodity) {
            $data->push([
                $commodity->commodity_name,
                number_format($commodity->total_collected),
                number_format($commodity->farmers_count),
            ]);
        }

        $data->push([]); // Empty row
        $data->push(['Farmers Who Collected']);
        $data->push(['Farmer Name', 'Registration Number']);

        // Add farmers list
        foreach ($this->collectionInsights['farmers_collected'] as $farmer) {
            $data->push([
                $farmer->full_name,
                $farmer->registration_number,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Commodity Collections Summary',
            '',
            '',
        ];
    }

    public function title(): string
    {
        return 'Commodity Collections';
    }
}

class FinancialInsightsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $financialInsights;

    public function __construct($financialInsights)
    {
        $this->financialInsights = $financialInsights;
    }

    public function collection()
    {
        $data = collect();

        if ($this->financialInsights['type'] === 'co-funded') {
            $data->push(['Financial Insights - Co-funded Season']);
            $data->push(['Metric', 'Value']);
            $data->push(['Total Loan Amount', '₦' . number_format($this->financialInsights['total_loan_amount'], 2)]);
            $data->push(['Disbursed Amount (50%)', '₦' . number_format($this->financialInsights['total_disbursed'], 2)]);
            $data->push(['Equity Held (50%)', '₦' . number_format($this->financialInsights['equity_held'], 2)]);
            $data->push(['Expected Payments', '₦' . number_format($this->financialInsights['expected_payments'], 2)]);
            $data->push(['Actual Payments', '₦' . number_format($this->financialInsights['actual_payments'], 2)]);
            $data->push(['Payment Rate', number_format($this->financialInsights['payment_rate'], 2) . '%']);
            $data->push(['Outstanding Amount', '₦' . number_format($this->financialInsights['outstanding_amount'], 2)]);
            $data->push([]);
            $data->push(['Application Status', 'Count']);
            $data->push(['Paid Applications', $this->financialInsights['paid_applications']]);
            $data->push(['Pending Applications', $this->financialInsights['pending_applications']]);
            $data->push(['Total Approved', $this->financialInsights['approved_applications']]);
        } else {
            $data->push(['Financial Insights - Complete Loan Season']);
            $data->push(['Metric', 'Value']);
            $data->push(['Total Loan Amount', '₦' . number_format($this->financialInsights['total_loan_amount'], 2)]);
            $data->push(['Collected Applications', $this->financialInsights['collected_applications']]);
            $data->push(['Returned Applications', $this->financialInsights['returned_applications']]);
            $data->push(['Pending Collections', $this->financialInsights['pending_collections']]);
            $data->push(['Pending Returns', $this->financialInsights['pending_returns']]);
            $data->push(['Collection Rate', number_format($this->financialInsights['collection_rate'], 2) . '%']);
            $data->push(['Return Rate', number_format($this->financialInsights['return_rate'], 2) . '%']);
            $data->push(['Payment Required', 'No']);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Financial Insights',
            '',
        ];
    }

    public function title(): string
    {
        return 'Financial Insights';
    }
}

class FarmerDetailsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $season;
    protected $farmerDetails;

    public function __construct(Season $season, $farmerDetails)
    {
        $this->season = $season;
        $this->farmerDetails = $farmerDetails;
    }

    public function collection()
    {
        $data = collect();

        // Debug information
        $data->push(['Debug - Farmer Details Count: ' . (is_array($this->farmerDetails) ? count($this->farmerDetails) : 'Not an array')]);
        $data->push(['Debug - Farmer Details: ' . (is_array($this->farmerDetails) ? 'Array' : gettype($this->farmerDetails))]);

        if (!$this->farmerDetails || !is_array($this->farmerDetails) || count($this->farmerDetails) === 0) {
            $data->push(['No farmer details available for this season']);
            $data->push(['This might indicate no approved applications exist for this season']);
            return $data;
        }

        $data->push(['Season: ' . $this->season->name . ' (' . ucfirst(str_replace('-', ' ', $this->season->loan_type)) . ')']);
        $data->push(['Total Farmers: ' . count($this->farmerDetails)]);
        $data->push([]); // Empty row

        // Headers - Only the requested fields
        $data->push([
            'Farmer Name',
            'Phone',
            'BVN',
            'NIN',
            'Total Loan (₦)',
            'Disbursed Amount (₦)',
            'Total Commodity Allocated'
        ]);

        // Farmer data - Only the requested fields
        foreach ($this->farmerDetails as $farmer) {
            $data->push([
                $farmer['farmer_name'] ?? 'No Name',
                $farmer['phone'] ?? 'No Phone',
                $farmer['bvn'] ?: 'N/A',
                $farmer['nin'] ?: 'N/A',
                isset($farmer['total_loan']) ? number_format($farmer['total_loan'], 2) : '0.00',
                isset($farmer['disbursed_amount']) ? number_format($farmer['disbursed_amount'], 2) : '0.00',
                isset($farmer['total_commodity_allocated']) ? number_format($farmer['total_commodity_allocated'], 2) . ' ' . ($farmer['commodity_units'] ?: 'units') : '0 units'
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Farmer Name',
            'Phone',
            'BVN',
            'NIN',
            'Total Loan (₦)',
            'Disbursed Amount (₦)',
            'Total Commodity Allocated'
        ];
    }

    public function title(): string
    {
        return 'Farmer Details';
    }
}

class CommodityReturnsSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $commodityInsights;

    public function __construct($commodityInsights)
    {
        $this->commodityInsights = $commodityInsights;
    }

    public function collection()
    {
        $data = collect();

        $data->push(['Expected Commodity Returns - Complete Loan Applications Only']);
        $data->push(['Note: Only farmers who collected without upfront payment are included']);
        $data->push([]);

        foreach ($this->commodityInsights as $commodity) {
            $data->push([
                'Commodity: ' . $commodity['commodity_name'],
                'Current Price: ₦' . number_format($commodity['current_price'], 2) . '/' . $commodity['unit'],
            ]);
            $data->push([]);
            $data->push(['Summary']);
            $data->push(['Expected Quantity', number_format($commodity['total_expected_quantity'], 2) . ' ' . $commodity['unit']]);
            $data->push(['Total Loan Value', '₦' . number_format($commodity['total_loan_value'], 2)]);
            $data->push(['Farmers Count', number_format($commodity['farmers_count'])]);
            $data->push(['Average per Farmer', number_format($commodity['total_expected_quantity'] / $commodity['farmers_count'], 2) . ' ' . $commodity['unit']]);
            $data->push([]);
            $data->push(['Farmer Details']);
            $data->push(['Farmer Name', 'Application Reference', 'Loan Amount', 'Expected Return']);

            foreach ($commodity['applications'] as $app) {
                $data->push([
                    $app['farmer_name'],
                    $app['reference_number'],
                    '₦' . number_format($app['total_loan'], 2),
                    number_format($app['expected_quantity'], 2) . ' ' . $commodity['unit'],
                ]);
            }
            $data->push([]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Expected Commodity Returns',
            '',
            '',
            '',
        ];
    }

    public function title(): string
    {
        return 'Commodity Returns';
    }
}
