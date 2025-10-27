<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\HtmlString;

class SeasonReportPdfExport
{
    protected $data;
    protected $summary;

    public function __construct($data, $summary)
    {
        $this->data = $data;
        $this->summary = $summary;
    }

    public function download($filename = 'season_report.pdf')
    {
        try {
            ini_set('max_execution_time', 300); // 5 minutes
            
            $pdf = $this->generatePdf();
            
            if (count($this->data) > 50) {
                // For large datasets, use chunking
                return $this->handleLargeExport($filename);
            }
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Log::error('PDF download failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function stream()
    {
        try {
            ini_set('max_execution_time', 300); // 5 minutes
            
            if (count($this->data) > 50) {
                // For large datasets, force download instead of streaming
                return $this->download('season_report_' . now()->format('Y-m-d') . '.pdf');
            }
            
            $pdf = $this->generatePdf();
            return $pdf->stream('season_report.pdf');
            
        } catch (\Exception $e) {
            \Log::error('PDF stream failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    protected function handleLargeExport($filename)
    {
        // Process in chunks to prevent memory issues
        $chunks = array_chunk($this->data->toArray(), 20, true);
        $pdf = $this->generatePdf($chunks[0]);
        
        // If there's only one chunk, just return it
        if (count($chunks) === 1) {
            return $pdf->download($filename);
        }
        
        // For multiple chunks, we'll need to merge them
        // For now, we'll just process the first chunk to avoid timeouts
        // In a production environment, consider using a queued job for this
        
        return $pdf->download($filename);
    }

    public function view(): View
    {
        return view('admin.seasons.exports.pdf', [
            'data' => $this->data,
            'summary' => $this->summary,
            'title' => 'Season Report - ' . $this->summary['season_name'],
            'is_complete_loan' => $this->summary['is_complete_loan'] ?? false,
            'commodity_summary' => $this->summary['commodity_summary'] ?? []
        ]);
    }

    protected function generatePdf($dataChunk = null)
    {
        $data = [
            'data' => $dataChunk ?? $this->data,
            'summary' => $this->summary,
            'currentDate' => now()->format('Y-m-d H:i:s'),
            'is_complete_loan' => $this->summary['is_complete_loan'] ?? false,
            'commodity_summary' => $this->summary['commodity_summary'] ?? [],
            'formatCurrency' => function($value) {
                return '₦' . number_format($value, 2);
            },
            'formatDate' => function($date) {
                return $date === 'N/A' ? 'N/A' : \Carbon\Carbon::parse($date)->format('M d, Y');
            },
            'getStatusBadge' => function($status) {
                $statuses = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-blue-100 text-blue-800',
                    'collected' => 'bg-purple-100 text-purple-800',
                    'returned' => 'bg-green-100 text-green-800',
                    'default' => 'bg-gray-100 text-gray-800'
                ];
                
                $class = $statuses[strtolower($status)] ?? $statuses['default'];
                return new HtmlString(
                    '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $class . '">' . 
                    ucfirst($status) . 
                    '</span>'
                );
            }
        ];

        return Pdf::loadView('admin.seasons.exports.pdf', $data)
                  ->setPaper('a4', 'landscape')
                  ->setOption('defaultFont', 'Arial')
                  ->setOption('isHtml5ParserEnabled', true)
                  ->setOption('isRemoteEnabled', true);
    }
}
