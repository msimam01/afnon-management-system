<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EnhancedSeasonReportExport implements WithMultipleSheets
{
    protected $data;
    protected $summary;

    public function __construct(array $data, array $summary)
    {
        $this->data = $data;
        $this->summary = $summary;
    }

    public function sheets(): array
    {
        return [
            new SummarySheet($this->summary),
            new FarmerDetailsSheet($this->data, $this->summary['is_complete_loan']),
            new CommodityBreakdownSheet($this->summary['commodity_summary'], $this->summary['is_complete_loan']),
        ];
    }
}

class SummarySheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $summary;

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function collection()
    {
        $data = collect([
            ['Season Name', $this->summary['season_name']],
            ['Season Type', ucfirst($this->summary['season_type'])],
            ['Loan Type', $this->summary['loan_type'] === 'complete-loan' ? 'Complete Loan' : 'Co-Funded'],
            ['Start Date', $this->summary['start_date']],
            ['End Date', $this->summary['end_date']],
            ['Insurance Rate', $this->summary['insurance_rate'] . '%'],
            [],
            ['FINANCIAL SUMMARY', ''],
            ['Total Farmers', $this->summary['total_farmers']],
            ['Total Allocated Value', '₦' . number_format($this->summary['total_allocated_value'], 2)],
            ['Total Disbursed', '₦' . number_format($this->summary['total_disbursed'], 2)],
            ['Total Equity', '₦' . number_format($this->summary['total_equity'], 2)],
            ['Total Insurance', '₦' . number_format($this->summary['total_insurance'], 2)],
            [],
            ['DISTRIBUTION SUMMARY', ''],
            ['Total Allocated Quantity', number_format($this->summary['total_allocated_qty'], 2)],
            ['Total Collected', number_format($this->summary['total_collected'], 2)],
            ['Collection Rate', $this->summary['collection_rate'] . '%'],
        ]);

        if ($this->summary['is_complete_loan']) {
            $data->push(['']);
            $data->push(['RETURN SUMMARY', '']);
            $data->push(['Total Expected', number_format($this->summary['total_expected'], 2)]);
            $data->push(['Total Returned', number_format($this->summary['total_returned'], 2)]);
            $data->push(['Total Variance', number_format($this->summary['total_variance'], 2)]);
            $data->push(['Completion Rate', $this->summary['completion_rate'] . '%']);
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Metric', 'Value'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4a6da7']], 'font' => ['color' => ['rgb' => 'FFFFFF']]],
            'A8' => ['font' => ['bold' => true, 'size' => 11]],
            'A15' => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }

    public function title(): string
    {
        return 'Summary';
    }
}

class FarmerDetailsSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $data;
    protected $isCompleteLoan;

    public function __construct(array $data, bool $isCompleteLoan)
    {
        $this->data = $data;
        $this->isCompleteLoan = $isCompleteLoan;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->data as $farmer) {
            foreach ($farmer['commodities'] as $commodity) {
                $row = [
                    $farmer['farmer_name'],
                    $farmer['registration_number'],
                    $farmer['farmer_phone'],
                    $farmer['reference_number'],
                    $farmer['application_date'],
                    $farmer['farm_size'],
                    $commodity['name'],
                    $commodity['unit'],
                    $commodity['allocated'],
                    $commodity['collected'],
                ];

                if ($this->isCompleteLoan) {
                    $row[] = $commodity['expected'];
                    $row[] = $commodity['returned'];
                    $row[] = $commodity['variance'];
                }

                $row[] = $commodity['unit_price'];
                $row[] = $commodity['total_value'];
                $row[] = $farmer['collection_date'] ?? 'Pending';

                if ($this->isCompleteLoan) {
                    $row[] = $farmer['return_date'] ?? 'Pending';
                    $row[] = $farmer['shortfall_reason'];
                }

                $rows->push($row);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = [
            'Farmer Name',
            'Registration Number',
            'Phone',
            'Reference Number',
            'Application Date',
            'Farm Size (Ha)',
            'Commodity',
            'Unit',
            'Allocated Qty',
            'Collected Qty',
        ];

        if ($this->isCompleteLoan) {
            $headings[] = 'Expected Return';
            $headings[] = 'Returned Qty';
            $headings[] = 'Variance';
        }

        $headings[] = 'Unit Price';
        $headings[] = 'Total Value';
        $headings[] = 'Collection Date';

        if ($this->isCompleteLoan) {
            $headings[] = 'Return Date';
            $headings[] = 'Shortfall Reason';
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F4F8']]],
        ];
    }

    public function title(): string
    {
        return 'Farmer Details';
    }
}

class CommodityBreakdownSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $commoditySummary;
    protected $isCompleteLoan;

    public function __construct(array $commoditySummary, bool $isCompleteLoan)
    {
        $this->commoditySummary = $commoditySummary;
        $this->isCompleteLoan = $isCompleteLoan;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->commoditySummary as $commodity) {
            $row = [
                $commodity['name'],
                $commodity['unit'],
                $commodity['total_allocated'],
                $commodity['total_distributed'],
                $commodity['total_collected'],
                $commodity['collection_rate'] . '%',
            ];

            if ($this->isCompleteLoan) {
                $row[] = $commodity['total_expected'];
                $row[] = $commodity['total_returned'];
                $row[] = $commodity['variance'];
                $row[] = ($commodity['completion_rate'] ?? 0) . '%';
            }

            $row[] = '₦' . number_format($commodity['total_value'], 2);

            $rows->push($row);
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = [
            'Commodity',
            'Unit',
            'Total Allocated',
            'Total Distributed',
            'Total Collected',
            'Collection Rate',
        ];

        if ($this->isCompleteLoan) {
            $headings[] = 'Total Expected';
            $headings[] = 'Total Returned';
            $headings[] = 'Variance';
            $headings[] = 'Return Rate';
        }

        $headings[] = 'Total Value';

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D4EDDA']]],
        ];
    }

    public function title(): string
    {
        return 'Commodity Breakdown';
    }
}
