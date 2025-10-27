<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EnhancedSeasonReportExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithStyles
{
    protected $data;
    protected $summary;

    public function __construct($data, $summary)
    {
        $this->data = $data;
        $this->summary = $summary;
    }

    public function collection()
    {
        $rows = collect();
        
        // Convert data to array if it's a collection or object
        $data = $this->data;
        if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }
        
        // Ensure we have an array of items
        if (!is_array($data)) {
            $data = [$data];
        }
        
        foreach ($data as $row) {
            // Convert row to array if it's an object
            $rowData = is_object($row) ? (array)$row : $row;
            
            // Ensure we have the required fields
            if (!isset($rowData['farmer_name']) && isset($rowData['farmer'])) {
                $farmer = is_object($rowData['farmer']) ? (array)$rowData['farmer'] : $rowData['farmer'];
                $rowData['farmer_name'] = $farmer['full_name'] ?? $farmer['name'] ?? 'Unknown Farmer';
                $rowData['farmer_phone'] = $farmer['phone'] ?? 'N/A';
                $rowData['registration_number'] = $farmer['registration_number'] ?? 'N/A';
            }
            
            // Ensure commodities is an array
            if (isset($rowData['commodities'])) {
                if (is_object($rowData['commodities']) && method_exists($rowData['commodities'], 'toArray')) {
                    $rowData['commodities'] = $rowData['commodities']->toArray();
                } elseif (is_string($rowData['commodities'])) {
                    $rowData['commodities'] = json_decode($rowData['commodities'], true) ?? [];
                }
            } else {
                $rowData['commodities'] = [];
            }
            
            // Map the row data
            $mapped = $this->map($rowData);
            
            // Handle both single and multiple rows
            if (is_array($mapped) && !empty($mapped)) {
                if (is_array($mapped[0] ?? null)) {
                    // Multiple rows
                    foreach ($mapped as $mappedRow) {
                        $rows->push($mappedRow);
                    }
                } else {
                    // Single row
                    $rows->push($mapped);
                }
            }
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Season Report';
    }

    public function headings(): array
    {
        return [
            'Farmer Name',
            'Registration Number',
            'Phone',
            'Commodity',
            'Unit',
            'Allocated',
            'Collected',
            'Expected',
            'Returned',
            'Variance',
            'Unit Price',
            'Total Value',
            'Status',
            'Application Date',
            'Collection Date',
            'Return Date',
            'Shortfall Reason'
        ];
    }

    protected function getValue($data, $key, $default = 'N/A')
    {
        if (is_object($data)) {
            return $data->$key ?? $default;
        }
        return $data[$key] ?? $default;
    }

    public function map($row): array
    {
        $mappedRows = [];
        
        // Handle case where row is an object
        if (is_object($row)) {
            $row = $row->toArray();
        }
        
        // Ensure all required fields exist
        $farmerName = $this->getValue($row, 'farmer_name', 
            $this->getValue($row, 'full_name', 'Unknown Farmer')
        );
        
        $registrationNumber = $this->getValue($row, 'registration_number');
        $farmerPhone = $this->getValue($row, 'farmer_phone', $this->getValue($row, 'phone'));
        $commodities = $this->getValue($row, 'commodities', []);
        
        // If no commodities, still show the farmer with empty commodity data
        if (empty($commodities)) {
            return [
                $farmerName,
                $registrationNumber,
                $farmerPhone,
                'No commodities',
                '', '', '', '', '', '', '', '',
                ucfirst($this->getValue($row, 'status')),
                $this->getValue($row, 'application_date'),
                $this->getValue($row, 'collection_date'),
                $this->getValue($row, 'return_date'),
                $this->getValue($row, 'shortfall_reason')
            ];
        }
        
        // Create a row for each commodity
        foreach ($commodities as $index => $commodity) {
            if (is_object($commodity)) {
                $commodity = $commodity->toArray();
            }
            
            $mappedRows[] = [
                $index === 0 ? $farmerName : '',
                $index === 0 ? $registrationNumber : '',
                $index === 0 ? $farmerPhone : '',
                $this->getValue($commodity, 'name'),
                $this->getValue($commodity, 'unit', 'units'),
                $this->getValue($commodity, 'allocated', 0),
                $this->getValue($commodity, 'collected', 0),
                $this->summary['is_complete_loan'] ? $this->getValue($commodity, 'expected', 0) : 'N/A',
                $this->summary['is_complete_loan'] ? $this->getValue($commodity, 'returned', 0) : 'N/A',
                $this->summary['is_complete_loan'] ? $this->getValue($commodity, 'variance', 0) : 'N/A',
                $this->getValue($commodity, 'unit_price', 0),
                $this->getValue($commodity, 'total_value', 0),
                $index === 0 ? ucfirst($this->getValue($row, 'status')) : '',
                $index === 0 ? $this->getValue($row, 'application_date') : '',
                $index === 0 ? $this->getValue($row, 'collection_date') : '',
                $index === 0 ? $this->getValue($row, 'return_date') : '',
                $index === 0 ? $this->getValue($row, 'shortfall_reason') : ''
            ];
        }
        
        // Add a summary row for the farmer
        if (count($row['commodities']) > 1) {
            $mappedRows[] = [
                'TOTAL',
                '',
                '',
                '',
                '',
                $row['total_allocated_qty'],
                $row['total_collected_qty'],
                $this->summary['is_complete_loan'] ? $row['total_expected_qty'] : 'N/A',
                $this->summary['is_complete_loan'] ? $row['total_returned_qty'] : 'N/A',
                $this->summary['is_complete_loan'] ? $row['total_variance'] : 'N/A',
                '',
                $row['total_allocated_value'],
                '', '', '', '', ''
            ];
        }
        
        return $mappedRows;
    }

    public function styles(Worksheet $sheet)
    {
        // Header row styling
        $lastColumn = 'Q'; // Update to the last column
        $headerRange = 'A1:' . $lastColumn . '1';
        
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FFD9EAD3']
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => 'thin',
                    'color' => ['argb' => 'FF000000']
                ]
            ]
        ]);

        // Auto-size columns
        foreach(range('A', $lastColumn) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Add summary information
        $lastRow = $sheet->getHighestRow() + 2;
        
        // Season summary
        $sheet->setCellValue("A{$lastRow}", 'Season Summary:');
        $sheet->setCellValue("B{$lastRow}", $this->summary['season_name']);
        $sheet->getStyle("A{$lastRow}")->getFont()->setBold(true);
        
        $sheet->setCellValue("A" . ($lastRow + 1), 'Period:');
        $sheet->setCellValue("B" . ($lastRow + 1), 
            $this->summary['start_date'] . ' to ' . $this->summary['end_date']);
        
        $sheet->setCellValue("A" . ($lastRow + 2), 'Total Farmers:');
        $sheet->setCellValue("B" . ($lastRow + 2), $this->summary['total_farmers']);
        
        $sheet->setCellValue("A" . ($lastRow + 3), 'Total Allocated Value:');
        $sheet->setCellValue("B" . ($lastRow + 3), $this->summary['total_allocated_value']);
        
        $sheet->setCellValue("A" . ($lastRow + 4), 'Total Allocated Qty:');
        $sheet->setCellValue("B" . ($lastRow + 4), $this->summary['total_allocated_qty']);
        
        $sheet->setCellValue("A" . ($lastRow + 5), 'Total Collected:');
        $sheet->setCellValue("B" . ($lastRow + 5), $this->summary['total_collected']);
        
        if ($this->summary['is_complete_loan']) {
            $sheet->setCellValue("A" . ($lastRow + 6), 'Total Expected:');
            $sheet->setCellValue("B" . ($lastRow + 6), $this->summary['total_expected']);
            
            $sheet->setCellValue("A" . ($lastRow + 7), 'Total Returned:');
            $sheet->setCellValue("B" . ($lastRow + 7), $this->summary['total_returned']);
            
            $sheet->setCellValue("A" . ($lastRow + 8), 'Total Variance:');
            $sheet->setCellValue("B" . ($lastRow + 8), $this->summary['total_variance']);
            
            $lastRow += 3; // Adjust for additional rows
        } else {
            $lastRow += 1; // Just the completion rate remains
        }
        
        $sheet->setCellValue("A" . ($lastRow + 6), 'Completion Rate:');
        $sheet->setCellValue("B" . ($lastRow + 6), $this->summary['completion_rate'] . '%');
        
        // Add commodity summary if available
        if (!empty($this->summary['commodity_summary'])) {
            $lastRow = $sheet->getHighestRow() + 2;
            $sheet->setCellValue("A{$lastRow}", 'Commodity Summary:');
            $sheet->getStyle("A{$lastRow}")->getFont()->setBold(true);
            $lastRow++;
            
            // Add commodity summary headers
            $sheet->fromArray(
                ['Commodity', 'Allocated', 'Collected', 'Expected', 'Returned', 'Variance'],
                null,
                "A{$lastRow}"
            );
            
            // Style the headers
            $sheet->getStyle("A{$lastRow}:F{$lastRow}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['argb' => 'FFE6E6E6']
                ]
            ]);
            
            $lastRow++;
            
            // Add commodity data
            foreach ($this->summary['commodity_summary'] as $commodity) {
                $sheet->fromArray(
                    [
                        $commodity['name'],
                        $commodity['allocated'],
                        $commodity['collected'],
                        $this->summary['is_complete_loan'] ? $commodity['expected'] : 'N/A',
                        $this->summary['is_complete_loan'] ? $commodity['returned'] : 'N/A',
                        $this->summary['is_complete_loan'] ? ($commodity['expected'] - $commodity['returned']) : 'N/A'
                    ],
                    null,
                    "A{$lastRow}"
                );
                $lastRow++;
            }
        }

        // Format numeric columns
        $numericColumns = ['E', 'F', 'G', 'H', 'I', 'K', 'L'];
        foreach ($numericColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . $sheet->getHighestRow())
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
        }
        
        // Format currency columns
        $currencyColumns = ['K', 'L'];
        foreach ($currencyColumns as $col) {
            $sheet->getStyle($col . '2:' . $col . $sheet->getHighestRow())
                ->getNumberFormat()
                ->setFormatCode('"₦"#,##0.00');
        }

        // Style total rows
        $sheet->getStyle('A1:' . $lastColumn . $sheet->getHighestRow())
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin');
            
        // Style summary sections
        $lastDataRow = $sheet->getHighestRow();
        $sheet->getStyle("A{$lastRow}:F{$lastDataRow}")
            ->getBorders()
            ->getOutline()
            ->setBorderStyle('medium');

        return [];
    }
}
