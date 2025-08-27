<?php

namespace App\Exports;

use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApplicationsExport implements FromCollection, WithHeadings
{
    protected $applications;

    public function __construct($applications)
    {
        $this->applications = $applications;
    }

    public function collection()
    {
        return $this->applications->map(function ($app) {
            return [
                'Reg. No'      => $app->farmer->registration_number,
                'Farmer'       => $app->farmer->full_name,
                'Season'       => $app->season->name,
                'Total Loan'   => $app->total_loan,
                'Equity Held'   => $app->equity,
                'Disbursed Amount'   => $app->disbursed_amount,
                'Status'       => ucfirst($app->status),
                'Payment Status'       => ucfirst($app->payment_status),
                'Created At'   => $app->created_at->format('Y-m-d'),
            ];
        });
    }
    public function headings(): array
    {
        return [
            'Reg. No',
            'Farmer',
            'Season',
            'Total Loan',
            'Equity Held',
            'Disbursed Amount',
            'Status',
            'Payment Status',
            'Created At',
        ];
    }
}
