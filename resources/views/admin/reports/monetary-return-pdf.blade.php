<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monetary Return Report - {{ $return->tx_ref }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #F3F4F6;
            padding: 8px 12px;
            font-weight: bold;
            color: #374151;
            border-left: 4px solid #4F46E5;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 8px;
            font-weight: bold;
            color: #6B7280;
            border-bottom: 1px solid #E5E7EB;
        }
        .info-value {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #E5E7EB;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background-color: #4F46E5;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 10px;
            border: 1px solid #E5E7EB;
        }
        .table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .amount {
            font-weight: bold;
            color: #059669;
        }
        .status-paid {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
        }
        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monetary Return Report</h1>
        <p>Transaction Reference: {{ $return->tx_ref }}</p>
        <p>Generated on: {{ now()->format('F d, Y \a\t H:i A') }}</p>
    </div>

    <!-- Payment Information -->
    <div class="section">
        <div class="section-title">Payment Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Transaction Reference:</div>
                <div class="info-value">{{ $return->tx_ref }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Invoice Number:</div>
                <div class="info-value">{{ $return->invoice_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                            <div class="info-label">Amount Paid:</div>
                            <div class="info-value amount">N{{ number_format($return->amount, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Payment Status:</div>
                <div class="info-value">
                    <span class="status-{{ $return->status }}">{{ ucfirst($return->status) }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Payment Provider:</div>
                <div class="info-value">{{ ucfirst($return->payment_provider ?? 'N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Payment Date:</div>
                <div class="info-value">{{ $return->created_at->format('F d, Y H:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Farmer Information -->
    <div class="section">
        <div class="section-title">Farmer Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value">{{ $return->application->farmer->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Registration Number:</div>
                <div class="info-value">{{ $return->application->farmer->registration_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone Number:</div>
                <div class="info-value">{{ $return->application->farmer->phone }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Application Reference:</div>
                <div class="info-value">{{ $return->application->reference_number }}</div>
            </div>
        </div>
    </div>

    <!-- Season Information -->
    <div class="section">
        <div class="section-title">Season Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Season Name:</div>
                <div class="info-value">{{ $return->application->season->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Return Deadline:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($return->application->season->return_deadline)->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Commodity Allocations -->
    <div class="section">
        <div class="section-title">Commodity Allocations</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Commodity</th>
                    <th>Allocated Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($return->application->commodity_allocations as $allocation)
                <tr>
                    <td>{{ $allocation->commodity_name }}</td>
                    <td>{{ number_format($allocation->allocated_quantity) }} {{ $allocation->unit ?? 'units' }}</td>
                    <td>N{{ number_format($allocation->unit_price ?? 0, 2) }}</td>
                    <td class="amount">N{{ number_format(($allocation->allocated_quantity * ($allocation->unit_price ?? 0)), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Payment Calculation Details -->
    @if($return->calculation_details)
    <div class="section">
        <div class="section-title">Payment Calculation</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Calculation Method:</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $return->calculation_method)) }}</div>
            </div>
            @if(isset($return->calculation_details['breakdown']))
                @foreach($return->calculation_details['breakdown'] as $item)
                <div class="info-row">
                    <div class="info-label">{{ $item['description'] ?? 'Item' }}:</div>
                    <div class="info-value">N{{ number_format($item['amount'] ?? 0, 2) }}</div>
                </div>
                @endforeach
            @endif
            <div class="info-row">
                <div class="info-label" style="font-weight: bold; background-color: #F3F4F6;">Total Amount:</div>
                <div class="info-value amount" style="font-weight: bold; background-color: #F3F4F6;">N{{ number_format($return->amount, 2) }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the AFNEN Management System</p>
        <p>For any inquiries, please contact the system administrator</p>
    </div>
</body>
</html>
