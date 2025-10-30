<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Season Report - {{ $summary['season_name'] }}</title>
    <style>
        @page { margin: 1cm; font-family: Arial, sans-serif; }
        body { font-family: Arial, sans-serif; font-size: 9pt; line-height: 1.4; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #4a6da7; padding-bottom: 10px; }
        .header h1 { margin: 0 0 5px 0; color: #2c3e50; font-size: 16pt; }
        .header p { margin: 0; color: #666; font-size: 9pt; }
        h2 { background-color: #4a6da7; color: white; padding: 5px 10px; font-size: 11pt; margin: 15px 0 10px 0; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 8pt; }
        th, td { border: 1px solid #ddd; padding: 4px 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; color: #333; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .farmer-section { margin-bottom: 15px; border: 1px solid #e0e0e0; border-radius: 4px; overflow: hidden; page-break-inside: avoid; }
        .farmer-header { background-color: #f8f9fa; margin: 0; }
        .farmer-header td, .farmer-header th { border: none; padding: 4px 8px; font-size: 8.5pt; }
        .farmer-header th { text-align: right; width: 15%; background-color: transparent; }
        .commodity-details { margin: 0; border-top: 1px solid #e0e0e0; }
        .commodity-details th { background-color: #f1f5ff; }
        .farmer-total { font-weight: bold; background-color: #f0f7ff; }
        .shortfall-reason { padding: 5px 8px; background-color: #fff8e1; border-left: 3px solid #ffc107; margin: 5px 8px; font-size: 8.5pt; }
        .footer { margin-top: 20px; padding-top: 10px; text-align: center; font-size: 8pt; color: #777; border-top: 1px solid #e0e0e0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Comprehensive Season Report</h1>
        <p><strong>{{ $summary['season_name'] }}</strong> ({{ $summary['loan_type'] === 'complete-loan' ? 'Complete Loan' : 'Co-Funded' }})</p>
        <p>{{ $summary['start_date'] }} - {{ $summary['end_date'] }}</p>
        <p>Generated: {{ now()->format('F d, Y H:i:s') }}</p>
    </div>

    <!-- Summary Section -->
    <h2>Executive Summary</h2>
    <table>
        <tr>
            <th>Total Farmers</th>
            <td>{{ $summary['total_farmers'] }}</td>
            <th>Total Allocated Value</th>
            <td class="text-right">₦{{ number_format($summary['total_allocated_value'], 2) }}</td>
        </tr>
        <tr>
            <th>Total Disbursed</th>
            <td class="text-right">₦{{ number_format($summary['total_disbursed'], 2) }}</td>
            <th>Total Equity</th>
            <td class="text-right">₦{{ number_format($summary['total_equity'], 2) }}</td>
        </tr>
        <tr>
            <th>Total Insurance</th>
            <td class="text-right">₦{{ number_format($summary['total_insurance'], 2) }}</td>
            <th>Collection Rate</th>
            <td class="text-right">{{ $summary['collection_rate'] }}%</td>
        </tr>
        @if($summary['is_complete_loan'])
        <tr>
            <th>Return Completion Rate</th>
            <td class="text-right">{{ $summary['completion_rate'] }}%</td>
            <th>Total Variance</th>
            <td class="text-right">{{ number_format($summary['total_variance'], 2) }}</td>
        </tr>
        @endif
    </table>

    <!-- Commodity Summary -->
    <h2>Commodity Distribution Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                <th class="text-right">Allocated</th>
                <th class="text-right">Collected</th>
                <th class="text-right">Collection %</th>
                @if($summary['is_complete_loan'])
                <th class="text-right">Expected</th>
                <th class="text-right">Returned</th>
                <th class="text-right">Variance</th>
                <th class="text-right">Return %</th>
                @endif
                <th class="text-right">Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary['commodity_summary'] as $commodity)
            <tr>
                <td>{{ $commodity['name'] }} ({{ $commodity['unit'] }})</td>
                <td class="text-right">{{ number_format($commodity['total_allocated'], 2) }}</td>
                <td class="text-right">{{ number_format($commodity['total_collected'], 2) }}</td>
                <td class="text-right">{{ $commodity['collection_rate'] }}%</td>
                @if($summary['is_complete_loan'])
                <td class="text-right">{{ number_format($commodity['total_expected'], 2) }}</td>
                <td class="text-right">{{ number_format($commodity['total_returned'], 2) }}</td>
                <td class="text-right">{{ number_format($commodity['variance'], 2) }}</td>
                <td class="text-right">{{ $commodity['completion_rate'] ?? 0 }}%</td>
                @endif
                <td class="text-right">₦{{ number_format($commodity['total_value'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Farmer Details -->
    <h2>Farmer-Level Details</h2>
    @foreach($data as $farmer)
    <div class="farmer-section">
        <table class="farmer-header">
            <tr>
                <th>Farmer:</th>
                <td><strong>{{ $farmer['farmer_name'] }}</strong></td>
                <th>Reg #:</th>
                <td>{{ $farmer['registration_number'] }}</td>
                <th>Phone:</th>
                <td>{{ $farmer['farmer_phone'] }}</td>
            </tr>
            <tr>
                <th>Farm Size:</th>
                <td>{{ $farmer['farm_size'] }} Ha</td>
                <th>Total Loan:</th>
                <td>₦{{ number_format($farmer['total_loan'], 2) }}</td>
                <th>Disbursed:</th>
                <td>₦{{ number_format($farmer['disbursed_amount'], 2) }}</td>
            </tr>
        </table>

        @if(!empty($farmer['commodities']))
        <table class="commodity-details">
            <thead>
                <tr>
                    <th>Commodity</th>
                    <th class="text-right">Allocated</th>
                    <th class="text-right">Collected</th>
                    @if($summary['is_complete_loan'])
                    <th class="text-right">Expected</th>
                    <th class="text-right">Returned</th>
                    <th class="text-right">Variance</th>
                    @endif
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($farmer['commodities'] as $commodity)
                <tr>
                    <td>{{ $commodity['name'] }} ({{ $commodity['unit'] }})</td>
                    <td class="text-right">{{ number_format($commodity['allocated'], 2) }}</td>
                    <td class="text-right">{{ number_format($commodity['collected'], 2) }}</td>
                    @if($summary['is_complete_loan'])
                    <td class="text-right">{{ number_format($commodity['expected'], 2) }}</td>
                    <td class="text-right">{{ number_format($commodity['returned'], 2) }}</td>
                    <td class="text-right">{{ number_format($commodity['variance'], 2) }}</td>
                    @endif
                    <td class="text-right">₦{{ number_format($commodity['unit_price'], 2) }}</td>
                    <td class="text-right">₦{{ number_format($commodity['total_value'], 2) }}</td>
                </tr>
                @endforeach

                @if(count($farmer['commodities']) > 1)
                <tr class="farmer-total">
                    <td><strong>Total:</strong></td>
                    <td class="text-right"><strong>{{ number_format($farmer['total_allocated_qty'], 2) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($farmer['total_collected_qty'], 2) }}</strong></td>
                    @if($summary['is_complete_loan'])
                    <td class="text-right"><strong>{{ number_format($farmer['total_expected_qty'], 2) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($farmer['total_returned_qty'], 2) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($farmer['total_variance'], 2) }}</strong></td>
                    @endif
                    <td></td>
                    <td class="text-right"><strong>₦{{ number_format($farmer['total_allocated_value'], 2) }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif

        @if($farmer['shortfall_reason'] !== 'N/A')
        <div class="shortfall-reason">
            <strong>Shortfall Reason:</strong> {{ $farmer['shortfall_reason'] }}
        </div>
        @endif

        <table style="border: none; margin: 0;">
            <tr style="border: none;">
                <td style="border: none; padding: 5px 8px; font-size: 8pt; color: #666;">
                    <span style="margin-right: 15px;"><strong>Applied:</strong> {{ $farmer['application_date'] }}</span>
                    <span style="margin-right: 15px;"><strong>Collected:</strong> {{ $farmer['collection_date'] ?? 'Pending' }}</span>
                    @if($summary['is_complete_loan'])
                    <span><strong>Returned:</strong> {{ $farmer['return_date'] ?? 'Pending' }}</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endforeach

    <div class="footer">
        <p>End of Report - Generated by {{ config('app.name') }}</p>
    </div>
</body>
</html>
