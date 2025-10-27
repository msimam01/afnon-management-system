<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Season Report - {{ $summary['season_name'] }}</title>
    <style>
        @page {
            margin: 1cm;
            font-family: Arial, sans-serif;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #4a6da7;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #2c3e50;
            font-size: 16pt;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 9pt;
        }
        .summary, .commodity-summary, .details {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        h2 {
            background-color: #4a6da7;
            color: white;
            padding: 5px 10px;
            font-size: 11pt;
            margin: 15px 0 10px 0;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 8pt;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }
        .text-right {
            text-align: right;
        }
        .farmer-section {
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
            page-break-inside: avoid;
        }
        .farmer-header {
            background-color: #f8f9fa;
            margin: 0;
        }
        .farmer-header td, .farmer-header th {
            border: none;
            padding: 4px 8px;
            font-size: 8.5pt;
        }
        .farmer-header th {
            text-align: right;
            width: 15%;
            background-color: transparent;
        }
        .commodity-details {
            margin: 0;
            border-top: 1px solid #e0e0e0;
        }
        .commodity-details th {
            background-color: #f1f5ff;
        }
        .farmer-total {
            font-weight: bold;
            background-color: #f0f7ff;
        }
        .farmer-footer {
            background-color: #f8f9fa;
            padding: 4px 8px;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #e0e0e0;
        }
        .farmer-footer span {
            margin-right: 15px;
        }
        .shortfall-reason {
            padding: 5px 8px;
            background-color: #fff8e1;
            border-left: 3px solid #ffc107;
            margin: 5px 8px;
            font-size: 8.5pt;
        }
        .no-commodities {
            padding: 8px;
            font-style: italic;
            color: #666;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 8pt;
            color: #777;
            border-top: 1px solid #e0e0e0;
        }
        .page-number:after {
            content: counter(page);
        }
        .page-count:after {
            content: counter(pages);
        }
        @media print {
            .farmer-section {
                page-break-inside: avoid;
            }
            .summary-table, .commodity-summary-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Season Report</h1>
        <p>{{ $summary['season_name'] }} ({{ $summary['start_date'] }} - {{ $summary['end_date'] }})</p>
        <p>Generated on: {{ $currentDate }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <table class="summary-table">
            <tr>
                <th>Season Name</th>
                <td>{{ $summary['season_name'] }}</td>
                <th>Total Farmers</th>
                <td>{{ $summary['total_farmers'] }}</td>
            </tr>
            <tr>
                <th>Period</th>
                <td>{{ $summary['start_date'] }} to {{ $summary['end_date'] }}</td>
                <th>Total Allocated Value</th>
                <td>{{ $formatCurrency($summary['total_allocated_value']) }}</td>
            </tr>
            <tr>
                <th>Total Allocated Qty</th>
                <td>{{ number_format($summary['total_allocated_qty'], 2) }}</td>
                <th>Total Collected</th>
                <td>{{ number_format($summary['total_collected'], 2) }}</td>
            </tr>
            @if($is_complete_loan)
            <tr>
                <th>Total Expected</th>
                <td>{{ number_format($summary['total_expected'], 2) }}</td>
                <th>Total Returned</th>
                <td>{{ number_format($summary['total_returned'], 2) }}</td>
            </tr>
            <tr>
                <th>Total Variance</th>
                <td colspan="3">{{ number_format($summary['total_variance'], 2) }}</td>
            </tr>
            @endif
            <tr>
                <th>Completion Rate</th>
                <td colspan="3">{{ $summary['completion_rate'] }}%</td>
            </tr>
        </table>
    </div>

    @if(!empty($summary['commodity_summary']))
    <div class="commodity-summary">
        <h2>Commodity Allocation & Distribution Summary</h2>
        <table class="commodity-summary-table">
            <thead>
                <tr>
                    <th rowspan="2">Commodity</th>
                    <th colspan="2" class="text-center">Allocation</th>
                    <th colspan="2" class="text-center">Distribution</th>
                    <th colspan="2" class="text-center">Collection</th>
                    @if($summary['is_complete_loan'])
                    <th colspan="3" class="text-center">Returns</th>
                    <th class="text-center">Completion</th>
                    @endif
                </tr>
                <tr>
                    <!-- Allocation -->
                    <th class="text-right">Total Stock</th>
                    <th class="text-right">Distributed</th>
                    
                    <!-- Distribution -->
                    <th class="text-right">% Used</th>
                    <th class="text-right">Per Farmer</th>
                    
                    <!-- Collection -->
                    <th class="text-right">Total</th>
                    <th class="text-right">% Collected</th>
                    
                    @if($summary['is_complete_loan'])
                    <!-- Returns -->
                    <th class="text-right">Expected</th>
                    <th class="text-right">Returned</th>
                    <th class="text-right">Variance</th>
                    <th class="text-right">Rate</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($summary['commodity_summary'] as $item)
                @php
                    $distributedPct = $item['total_allocated'] > 0 
                        ? round(($item['total_distributed'] / $item['total_allocated']) * 100, 2) 
                        : 0;
                    $avgPerFarmer = $summary['total_farmers'] > 0 
                        ? $item['total_distributed'] / $summary['total_farmers'] 
                        : 0;
                    $collectedPct = $item['total_distributed'] > 0 
                        ? round(($item['total_collected'] / $item['total_distributed']) * 100, 2) 
                        : 0;
                @endphp
                <tr>
                    <td>{{ $item['name'] }} ({{ $item['unit'] }})</td>
                    
                    <!-- Allocation -->
                    <td class="text-right">{{ number_format($item['total_allocated'], 2) }}</td>
                    <td class="text-right">{{ number_format($item['total_distributed'], 2) }}</td>
                    
                    <!-- Distribution -->
                    <td class="text-right">{{ $distributedPct }}%</td>
                    <td class="text-right">{{ number_format($avgPerFarmer, 2) }}</td>
                    
                    <!-- Collection -->
                    <td class="text-right">{{ number_format($item['total_collected'], 2) }}</td>
                    <td class="text-right">{{ $collectedPct }}%</td>
                    
                    @if($summary['is_complete_loan'])
                    <!-- Returns -->
                    <td class="text-right">{{ number_format($item['total_expected'], 2) }}</td>
                    <td class="text-right">{{ number_format($item['total_returned'], 2) }}</td>
                    <td class="text-right">{{ number_format($item['variance'], 2) }}</td>
                    <td class="text-right">{{ $item['completion_rate'] }}%</td>
                    @endif
                </tr>
                @endforeach
                
                <!-- Totals Row -->
                <tr class="font-bold bg-gray-100">
                    <td>Total</td>
                    <td class="text-right">{{ number_format(collect($summary['commodity_summary'])->sum('total_allocated'), 2) }}</td>
                    <td class="text-right">{{ number_format(collect($summary['commodity_summary'])->sum('total_distributed'), 2) }}</td>
                    <td class="text-right">
                        @php
                            $totalAllocated = collect($summary['commodity_summary'])->sum('total_allocated');
                            $totalDistributed = collect($summary['commodity_summary'])->sum('total_distributed');
                            $totalDistributedPct = $totalAllocated > 0 ? round(($totalDistributed / $totalAllocated) * 100, 2) : 0;
                        @endphp
                        {{ $totalDistributedPct }}%
                    </td>
                    <td class="text-right">
                        @php
                            $avgTotalPerFarmer = $summary['total_farmers'] > 0 
                                ? $totalDistributed / $summary['total_farmers'] 
                                : 0;
                        @endphp
                        {{ number_format($avgTotalPerFarmer, 2) }}
                    </td>
                    <td class="text-right">{{ number_format(collect($summary['commodity_summary'])->sum('total_collected'), 2) }}</td>
                    <td class="text-right">
                        @php
                            $totalCollected = collect($summary['commodity_summary'])->sum('total_collected');
                            $totalCollectedPct = $totalDistributed > 0 ? round(($totalCollected / $totalDistributed) * 100, 2) : 0;
                        @endphp
                        {{ $totalCollectedPct }}%
                    </td>
                    
                    @if($summary['is_complete_loan'])
                    <td class="text-right">{{ number_format(collect($summary['commodity_summary'])->sum('total_expected'), 2) }}</td>
                    <td class="text-right">{{ number_format(collect($summary['commodity_summary'])->sum('total_returned'), 2) }}</td>
                    <td class="text-right">{{ number_format(collect($summary['commodity_summary'])->sum('variance'), 2) }}</td>
                    <td class="text-right">
                        @php
                            $totalExpected = collect($summary['commodity_summary'])->sum('total_expected');
                            $totalReturned = collect($summary['commodity_summary'])->sum('total_returned');
                            $totalCompletionRate = $totalExpected > 0 ? round(($totalReturned / $totalExpected) * 100, 2) : 0;
                        @endphp
                        {{ $totalCompletionRate }}%
                    </td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="details">
        <h2>Farmer Details</h2>
        @foreach($data as $row)
        <div class="farmer-section">
            <table class="farmer-header">
                <tr>
                    <th>Farmer Name:</th>
                    <td>{{ $row['farmer_name'] }}</td>
                    <th>Phone:</th>
                    <td>{{ $row['farmer_phone'] ?? 'N/A' }}</td>
                    <th>Reg. #:</th>
                    <td>{{ $row['registration_number'] ?? 'N/A' }}</td>
                    <th>Status:</th>
                    <td>{{ ucfirst($row['status'] ?? 'N/A') }}</td>
                </tr>
            </table>
            
            @if(!empty($row['commodities']))
            <table class="commodity-details">
                <thead>
                    <tr>
                        <th>Commodity</th>
                        <th>Unit</th>
                        <th>Allocated</th>
                        <th>Collected</th>
                        @if($is_complete_loan)
                        <th>Expected</th>
                        <th>Returned</th>
                        <th>Variance</th>
                        @endif
                        <th>Unit Price</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($row['commodities'] as $commodity)
                    <tr>
                        <td>{{ $commodity['name'] }}</td>
                        <td>{{ $commodity['unit'] }}</td>
                        <td class="text-right">{{ number_format($commodity['allocated'], 2) }}</td>
                        <td class="text-right">{{ number_format($commodity['collected'], 2) }}</td>
                        @if($is_complete_loan)
                        <td class="text-right">{{ number_format($commodity['expected'], 2) }}</td>
                        <td class="text-right">{{ number_format($commodity['returned'], 2) }}</td>
                        <td class="text-right">{{ number_format($commodity['variance'], 2) }}</td>
                        @endif
                        <td class="text-right">{{ $formatCurrency($commodity['unit_price']) }}</td>
                        <td class="text-right">{{ $formatCurrency($commodity['total_value']) }}</td>
                    </tr>
                    @endforeach
                    
                    @if(count($row['commodities']) > 1)
                    <tr class="farmer-total">
                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                        <td class="text-right"><strong>{{ number_format($row['total_allocated_qty'], 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($row['total_collected_qty'], 2) }}</strong></td>
                        @if($is_complete_loan)
                        <td class="text-right"><strong>{{ number_format($row['total_expected_qty'], 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($row['total_returned_qty'], 2) }}</strong></td>
                        <td class="text-right"><strong>{{ number_format($row['total_variance'], 2) }}</strong></td>
                        @endif
                        <td></td>
                        <td class="text-right"><strong>{{ $formatCurrency($row['total_allocated_value']) }}</strong></td>
                    </tr>
                    @endif
                </tbody>
            </table>
            @else
            <p class="no-commodities">No commodity data available</p>
            @endif
            
            @if(isset($row['shortfall_reason']) && $row['shortfall_reason'] != 'N/A')
            <div class="shortfall-reason">
                <strong>Shortfall Reason:</strong> {{ $row['shortfall_reason'] }}
            </div>
            @endif
            
            <div class="farmer-footer">
                <span>Applied: {{ $row['application_date'] ?? 'N/A' }}</span>
                <span>Collected: {{ $row['collection_date'] ?? 'N/A' }}</span>
                @if(isset($row['return_date']) && $row['return_date'] != 'N/A')
                <span>Returned: {{ $row['return_date'] }}</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="footer">
        <p>Page <span class="page-number"></span> of <span class="page-count"></span></p>
        <p>Generated by {{ config('app.name') }}</p>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 15;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>
