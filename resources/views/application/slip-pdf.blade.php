@php
    // Tenant information
    $tenantName = tenant()->id ?? 'Tenant';
    $tenantDisplayName = ucfirst($tenantName);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Acknowledgement Slip - {{ $application->reference_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 8px;
            background: #fff;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .reference-number {
            font-size: 16px;
            font-weight: bold;
            border: 2px solid #000;
            padding: 8px 16px;
            display: inline-block;
            margin-top: 10px;
        }

        /* Section Headers */
        .section-header {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding: 8px 0;
            margin: 25px 0 15px 0;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background-color: #000;
            color: #fff;
            padding: 10px 8px;
            font-weight: bold;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        /* Info Grid */
        .info-section {
            margin: 20px 0;
        }

        .info-table {
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 6px 8px;
        }

        .info-table .label {
            font-weight: bold;
            width: 40%;
        }

        .info-table .value {
            width: 60%;
        }

        /* Financial Summary */
        .financial-summary {
            border: 2px solid #000;
            margin: 25px 0;
            padding: 15px;
        }

        .summary-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .summary-table td {
            padding: 8px;
            font-weight: bold;
        }

        .summary-table .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        /* QR Section */
        .qr-section {
            text-align: center;
            border: 1px dashed #000;
            padding: 20px;
            margin: 25px 0;
        }

        .qr-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .qr-text {
            font-size: 10px;
            margin-top: 10px;
            line-height: 1.5;
        }

        /* Signatures */
        .signatures {
            margin: 25px 0;
            page-break-inside: avoid;
            text-align: center;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .signature-block {
            width: 30%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-bottom: 8px;
            width: 100%;
        }

        .signature-title {
            font-weight: bold;
            font-size: 11px;
        }

        .signature-subtitle {
            font-size: 9px;
            color: #666;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #000;
            padding: 10px 20mm;
            font-size: 10px;
            text-align: center;
            background: #fff;
        }

        .footer-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Utilities */
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Acknowledgement Slip</h1>
        <div class="subtitle">{{ $tenantDisplayName }} Agricultural Finance Network</div>
        <div class="reference-number">REF: {{ $application->reference_number }}</div>
    </div>

    <!-- Farmer Information -->
    <div class="info-section">
        <div class="section-header">Farmer Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Full Name:</td>
                <td class="value">{{ $application->farmer->full_name }}</td>
                <td class="label">Registration Number:</td>
                <td class="value">{{ $application->farmer->registration_number }}</td>
            </tr>
            <tr>
                <td class="label">Phone Number:</td>
                <td class="value">{{ $application->farmer->phone }}</td>
                @if($application->farmer->bvn)
                <td class="label">BVN (Masked):</td>
                <td class="value">{{ substr($application->farmer->bvn, 0, 3) }}****{{ substr($application->farmer->bvn, -3) }}</td>
                @else
                <td class="label">BVN:</td>
                <td class="value">Not Provided</td>
                @endif
            </tr>
        </table>
    </div>

    <!-- Farm & Season Information -->
    <div class="info-section">
        <div class="section-header">Farm & Season Details</div>
        <table class="info-table">
            <tr>
                <td class="label">Current Season:</td>
                <td class="value">{{ $application->season->name }}</td>
                <td class="label">Farm Size:</td>
                <td class="value">{{ $application->farm->size }} hectares</td>
            </tr>
            <tr>
                <td class="label">Application Date:</td>
                <td class="value">{{ $application->created_at->format('d M, Y') }}</td>
                <td class="label">Generated On:</td>
                <td class="value">{{ now()->format('d M, Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <!-- Commodities Section -->
    <div class="section-header">Commodities Breakdown</div>
    <table>
        <thead>
            <tr>
                <th>Commodity Name</th>
                <th>Quantity Requested</th>
                <th>Unit</th>
                <th>Unit Price (₦)</th>
                <th>Total Value (₦)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($application->commodities as $commodity)
            <tr>
                <td>{{ $commodity->name }}</td>
                <td class="text-right">{{ number_format($commodity->pivot->quantity) }}</td>
                <td>{{ $commodity->unit }}</td>
                <td class="currency text-right">{{ number_format($commodity->price_per_unit, 2) }}</td>
                <td class="currency text-right">{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Commodity Allocation (if available) -->
    @if($application->commodity_allocations && $application->commodity_allocations->count() > 0)
    <div class="section-header">Detailed Commodity Allocation</div>
    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                <th>Qty/Hectare</th>
                <th>Farm Size (ha)</th>
                <th>Allocated Quantity</th>
                <th>Unit Price (₦)</th>
                <th>Total Value (₦)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($application->commodity_allocations as $allocation)
            <tr>
                <td>{{ $allocation->commodity_name }}</td>
                <td class="text-right">{{ $allocation->qty_per_hectare }}</td>
                <td class="text-right">{{ $application->farm->size }}</td>
                <td class="text-right">{{ $allocation->allocated_quantity }}</td>
                <td class="currency text-right">{{ number_format($allocation->unit_price, 2) }}</td>
                <td class="currency text-right">{{ number_format($allocation->total_value, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Financial Summary -->
    <div class="financial-summary">
        <div class="summary-title">Financial Summary</div>
        <table class="summary-table">
            @php
                $commodityTotal = 0;
                if($application->commodity_allocations && $application->commodity_allocations->count() > 0) {
                    $commodityTotal = $application->commodity_allocations->sum('total_value');
                } else {
                    foreach($application->commodities as $commodity) {
                        $commodityTotal += ($commodity->pivot->quantity ?? 0) * ($commodity->price_per_unit ?? 0);
                    }
                }
            @endphp
            
            <tr>
                <td>Base Commodity Value:</td>
                <td class="currency amount">₦{{ number_format($commodityTotal, 2) }}</td>
            </tr>
            
            @if($application->insurance_amount)
            <tr>
                <td>Insurance Premium ({{ $application->insurance_rate ?? 0 }}%):</td>
                <td class="currency amount">₦{{ number_format($application->insurance_amount, 2) }}</td>
            </tr>
            @endif
            
            @if($application->equity)
            <tr>
                <td>Equity Contribution (Held):</td>
                <td class="currency amount">₦{{ number_format($application->equity, 2) }}</td>
            </tr>
            @endif
            
            @if($application->total_loan)
            <tr style="border-top: 2px solid #000; font-weight: bold;">
                <td>TOTAL LOAN AMOUNT:</td>
                <td class="currency amount">₦{{ number_format($application->total_loan, 2) }}</td>
            </tr>
            @endif
            
            @if($application->disbursed_amount)
            <tr style="border-top: 1px solid #000; font-weight: bold;">
                <td>AMOUNT DISBURSED:</td>
                <td class="currency amount">₦{{ number_format($application->disbursed_amount, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- QR Code Section -->
    <div class="qr-section">
        <div class="qr-title">Digital Verification</div>
        <img src="data:image/svg+xml;base64,{!! base64_encode(
            QrCode::format('svg')->size(100)->backgroundColor(255,255,255)->color(0,0,0)->generate(url('/verify/'.$application->reference_number))
        ) !!}" alt="QR Code">
        <div class="qr-text">
            <strong>Scan for online verification</strong><br>
            Verification URL: {{ url('/verify/'.$application->reference_number) }}<br>
            This document can be verified online for authenticity
        </div>
    </div>

    <!-- Signatures -->
    {{-- <div class="signatures">
        <div class="signature-row">
            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">Official Seal</div>
                <div class="signature-subtitle">{{ $tenantDisplayName }} Office</div>
            </div>

            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">Authorized Officer</div>
                <div class="signature-subtitle">Processing Department</div>
            </div>

            <div class="signature-block">
                <div class="signature-line"></div>
                <div class="signature-title">Date</div>
                <div class="signature-subtitle">{{ now()->format('d/m/Y') }}</div>
            </div>
        </div>
    </div> --}}

    <!-- Footer -->
    <div class="footer">
        <div class="footer-title">{{ strtoupper($tenantDisplayName) }} AGRICULTURAL FINANCE NETWORK</div>
        <div>
            Generated: {{ now()->format('d/m/Y H:i') }} | 
            Document ID: {{ $application->uuid }} | 
            System: AFNON v1.0
        </div>
    </div>
</body>
</html>