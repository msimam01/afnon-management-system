<?php

namespace App\Exports;

use App\Models\MonetaryReturn;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonetaryReturnsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function query()
    {
        // Handle single model export
        if ($this->source instanceof MonetaryReturn) {
            return MonetaryReturn::where('id', $this->source->id)
                ->with(['application.farmer', 'application.commodity_allocations', 'application.season']);
        }

        // Handle bulk export with filters from request
        $query = MonetaryReturn::with(['application.farmer', 'application.commodity_allocations', 'application.season']);

        // Apply filters
        if ($this->source->filled('filter')) {
            $filter = $this->source->filter;
            $query->whereHas('application.farmer', function ($q) use ($filter) {
                $q->where('full_name', 'like', "%$filter%")
                  ->orWhere('registration_number', 'like', "%$filter%");
            });
        }

        if ($this->source->filled('season')) {
            $query->whereHas('application.season', function ($q) {
                $q->where('slug', $this->source->season);
            });
        }

        if ($this->source->filled('status')) {
            $query->where('status', $this->source->status);
        }

        if ($this->source->filled('from') && $this->source->filled('to')) {
            $query->whereBetween('created_at', [
                $this->source->from . " 00:00:00",
                $this->source->to . " 23:59:59"
            ]);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'Transaction Reference',
            'Invoice Number',
            'Farmer Name',
            'Registration Number',
            'Application Reference',
            'Season',
            'Commodities',
            'Amount Paid',
            'Payment Status',
            'Payment Provider',
            'Payment Date',
            'Verified At',
        ];
    }

    public function map($return): array
    {
        $commodities = $return->application->commodity_allocations->map(function ($allocation) {
            return $allocation->commodity_name . ' (' . $allocation->allocated_quantity . ')';
        })->implode(', ');

        return [
            $return->tx_ref,
            $return->invoice_number ?? 'N/A',
            $return->application->farmer->full_name,
            $return->application->farmer->registration_number,
            $return->application->reference_number,
            $return->application->season->name,
            $commodities,
            $return->amount,
            ucfirst($return->status),
            ucfirst($return->payment_provider ?? 'N/A'),
            $return->created_at->format('Y-m-d H:i:s'),
            $return->verified_at ? $return->verified_at->format('Y-m-d H:i:s') : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Transaction Reference
            'B' => 15, // Invoice Number
            'C' => 25, // Farmer Name
            'D' => 18, // Registration Number
            'E' => 20, // Application Reference
            'F' => 15, // Season
            'G' => 40, // Commodities
            'H' => 15, // Amount Paid
            'I' => 15, // Payment Status
            'J' => 15, // Payment Provider
            'K' => 20, // Payment Date
            'L' => 20, // Verified At
        ];
    }
}
