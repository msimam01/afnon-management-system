<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $season->name }} Season Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2563eb;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        .financial-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .financial-item {
            display: table-cell;
            width: 33.33%;
            padding: 8px;
            border: 1px solid #e5e7eb;
            text-align: center;
        }
        .commodity-section {
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
            padding: 15px;
        }
        .commodity-header {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1f2937;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $season->name }} Season Report</h1>
        <p>{{ ucfirst(str_replace('-', ' ', $season->loan_type)) }} Season</p>
        <p>Report Generated: {{ now()->format('F d, Y H:i') }}</p>
        <p>Season Period: {{ \Carbon\Carbon::parse($season->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($season->end_date)->format('M d, Y') }}</p>
    </div>

    <!-- Statistics Overview -->
    <div class="section">
        <div class="section-title">Season Statistics Overview</div>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-label">Total Applications</div>
                <div class="stat-value">{{ number_format($statistics['total_applications']) }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Approved Applications</div>
                <div class="stat-value">{{ number_format($statistics['approved_applications']) }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Farmers Collected</div>
                <div class="stat-value">{{ number_format($collectionInsights['farmers_collected_count']) }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Collection Rate</div>
                <div class="stat-value">{{ number_format($statistics['collection_rate'], 1) }}%</div>
            </div>
        </div>
    </div>

    <!-- Commodity Collections -->
    <div class="section">
        <div class="section-title">Commodity Collections Summary</div>
        <table>
            <thead>
                <tr>
                    <th>Commodity Name</th>
                    <th>Total Collected (Bags)</th>
                    <th>Number of Farmers</th>
                </tr>
            </thead>
            <tbody>
                @forelse($collectionInsights['commodity_collections'] as $commodity)
                    <tr>
                        <td>{{ $commodity->commodity_name }}</td>
                        <td>{{ number_format($commodity->total_collected) }}</td>
                        <td>{{ number_format($commodity->farmers_count) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #666;">No commodity collections found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Financial Insights -->
    <div class="section">
        <div class="section-title">Financial Insights</div>
        
        @if($financialInsights['type'] === 'co-funded')
            <p style="background-color: #dbeafe; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 11px;">
                <strong>Co-funded Season:</strong> Farmers pay 50% upfront (disbursed amount) to collect commodities. No commodity returns required.
            </p>
            
            <div class="financial-grid">
                <div class="financial-item">
                    <div class="stat-label">Total Loan Value</div>
                    <div class="stat-value">₦{{ number_format($financialInsights['total_loan_amount'], 2) }}</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">Disbursed (50%)</div>
                    <div class="stat-value">₦{{ number_format($financialInsights['total_disbursed'], 2) }}</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">Equity Held (50%)</div>
                    <div class="stat-value">₦{{ number_format($financialInsights['equity_held'], 2) }}</div>
                </div>
            </div>
            
            <div class="financial-grid">
                <div class="financial-item">
                    <div class="stat-label">Payments Received</div>
                    <div class="stat-value">₦{{ number_format($financialInsights['actual_payments'], 2) }}</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">Payment Rate</div>
                    <div class="stat-value">{{ number_format($financialInsights['payment_rate'], 1) }}%</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">Outstanding Amount</div>
                    <div class="stat-value">₦{{ number_format($financialInsights['outstanding_amount'], 2) }}</div>
                </div>
            </div>
        @else
            <p style="background-color: #dcfce7; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 11px;">
                <strong>Complete Loan Season:</strong> No upfront payment required. Farmers collect commodities and return equivalent value by deadline.
            </p>
            
            <div class="financial-grid">
                <div class="financial-item">
                    <div class="stat-label">Total Loan Value</div>
                    <div class="stat-value">₦{{ number_format($financialInsights['total_loan_amount'], 2) }}</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">Collected Applications</div>
                    <div class="stat-value">{{ number_format($financialInsights['collected_applications']) }}</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">Collection Rate</div>
                    <div class="stat-value">{{ number_format($financialInsights['collection_rate'], 1) }}%</div>
                </div>
            </div>
            
            <div class="financial-grid">
                <div class="financial-item">
                    <div class="stat-label">Returned Applications</div>
                    <div class="stat-value">{{ number_format($financialInsights['returned_applications']) }}</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">Return Rate</div>
                    <div class="stat-value">{{ number_format($financialInsights['return_rate'], 1) }}%</div>
                </div>
                <div class="financial-item">
                    <div class="stat-label">No Payment Required</div>
                    <div class="stat-value">✓ Confirmed</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Expected Commodity Returns (for Complete Loan seasons) -->
    @if($commodityInsights && count($commodityInsights) > 0)
        <div class="page-break"></div>
        <div class="section">
            <div class="section-title">Expected Commodity Returns - Complete Loan Applications Only</div>
            <p style="background-color: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 11px; border: 1px solid #ffeaa7;">
                <strong>Note:</strong> Only complete loan applications are included in this calculation. These farmers collected commodities without upfront payment and must return equivalent value by the deadline.
            </p>
            
            @foreach($commodityInsights as $commodity)
                <div class="commodity-section">
                    <div class="commodity-header">
                        {{ $commodity['commodity_name'] }} 
                        (Current Price: ₦{{ number_format($commodity['current_price'], 2) }}/{{ $commodity['unit'] }})
                    </div>
                    
                    <div class="financial-grid">
                        <div class="financial-item">
                            <div class="stat-label">Expected Quantity</div>
                            <div class="stat-value">{{ number_format($commodity['total_expected_quantity'], 2) }} {{ $commodity['unit'] }}</div>
                        </div>
                        <div class="financial-item">
                            <div class="stat-label">Total Loan Value</div>
                            <div class="stat-value">₦{{ number_format($commodity['total_loan_value'], 2) }}</div>
                        </div>
                        <div class="financial-item">
                            <div class="stat-label">Farmers Count</div>
                            <div class="stat-value">{{ number_format($commodity['farmers_count']) }}</div>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Farmer Name</th>
                                <th>Application Reference</th>
                                <th>Loan Amount</th>
                                <th>Expected Return</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commodity['applications'] as $app)
                                <tr>
                                    <td>{{ $app['farmer_name'] }}</td>
                                    <td>{{ $app['reference_number'] }}</td>
                                    <td>₦{{ number_format($app['total_loan'], 2) }}</td>
                                    <td>{{ number_format($app['expected_quantity'], 2) }} {{ $commodity['unit'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Footer -->
    <div style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #e5e7eb; padding-top: 10px;">
        <p>AFNON Management System - Season Report | Generated on {{ now()->format('F d, Y H:i') }}</p>
    </div>
</body>
</html>