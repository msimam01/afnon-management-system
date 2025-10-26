<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt - {{ $return->tx_ref }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #000;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #333;
        }
        .receipt-info {
            background-color: #f0f0f0;
            padding: 15px;
            border: 1px solid #333;
            margin-bottom: 25px;
        }
        .receipt-info h3 {
            margin: 0 0 10px 0;
            color: #000;
            font-weight: bold;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #e0e0e0;
            padding: 8px 12px;
            font-weight: bold;
            color: #000;
            border-left: 4px solid #000;
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
            color: #000;
            border-bottom: 1px solid #333;
        }
        .info-value {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }
        .table th {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 10px;
            border: 1px solid #333;
        }
        .table tr:nth-child(even) {
            background-color: #f0f0f0;
        }
        .amount {
            font-weight: bold;
            color: #000;
            font-size: 16px;
        }
        .status-paid {
            background-color: #e0e0e0;
            color: #000;
            padding: 4px 8px;
            border: 1px solid #333;
            font-size: 10px;
            font-weight: bold;
        }
        .total-section {
            background-color: #f9f9f9;
            padding: 15px;
            border: 2px solid #333;
            margin-top: 20px;
        }
        .total-section h3 {
            margin: 0 0 10px 0;
            color: #000;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #333;
            border-top: 1px solid #000;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="success-icon">✓</div>
        <h1>Payment Receipt</h1>
        <p>Transaction Reference: {{ $return->tx_ref }}</p>
        <p>Receipt Generated: {{ now()->format('F d, Y \a\t H:i A') }}</p>
    </div>

    <!-- Receipt Information -->
    <div class="receipt-info">
        <h3>Payment Confirmation</h3>
        <p>This receipt confirms that the monetary return payment has been successfully processed and verified.</p>
    </div>

    <!-- Payment Information -->
    <div class="section">
        <div class="section-title">Payment Details</div>
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
                <div class="info-label">Payment Status:</div>
                <div class="info-value">
                    <span class="status-paid">{{ ucfirst($return->status) }}</span>
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

    <!-- Total Amount -->
    <div class="total-section">
        <h3>Total Amount Paid</h3>
        <div class="amount">N{{ number_format($return->amount, 2) }}</div>
        <p style="margin: 10px 0 0 0; color: #6B7280;">This amount represents the monetary return obligation for the {{ $return->application->season->name }} season.</p>
    </div>

    <!-- Payment Calculation Details -->
    @if($return->calculation_details)
    <div class="section">
        <div class="section-title">Payment Calculation Breakdown</div>
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
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>Thank you for your payment!</strong></p>
        <p>This receipt serves as proof of payment for your monetary return obligation.</p>
        <p>For any inquiries, please contact the system administrator or your assigned agent.</p>
        <p>Generated by AFNEN Management System on {{ now()->format('F d, Y \a\t H:i A') }}</p>
    </div>
</body>
</html>
