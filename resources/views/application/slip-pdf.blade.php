@php
    // Tenant information
    $tenantName = tenant()->id ?? 'Tenant';
    $tenantDisplayName = ucfirst($tenantName);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Slip - {{ $application->reference_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            background: #fff;
            position: relative;
        }

        /* MOTTO Background */
        .motto-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.15;
            background-image: url('{{ asset('images/motto-background.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
        }

        /* Content overlay */
        .content-overlay {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.92);
            min-height: 100vh;
            padding: 10px;
        }

        /* Adjust header to work with background */
        .motto-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(2px);
        }

        /* Adjust sections to work with background */
        .info-section, .financial-summary, .qr-section {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(1px);
        }

        /* MOTTO Header styling */
        .motto-header {
            text-align: center;
            border: 3px double #000;
            padding: 20px;
            margin-bottom: 25px;
        }

        .motto-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .motto-subtitle {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .motto-description {
            font-size: 12px;
            font-style: italic;
            margin-bottom: 15px;
        }

        .reference-section {
            border: 2px solid #000;
            padding: 10px;
            margin-top: 15px;
            background: #fff;
        }

        .reference-label {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .reference-number {
            font-size: 14px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
        }

        /* Section Headers */
        .section-header {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding: 10px 0 5px 0;
            margin: 20px 0 12px 0;
            letter-spacing: 0.5px;
        }

        /* MOTTO Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
            font-size: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background-color: #333;
            color: #fff;
            padding: 8px 6px;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        td {
            padding: 6px;
            vertical-align: top;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        /* MOTTO specific table styling */
        .motto-table th {
            background-color: #000;
            font-weight: bold;
        }

        .motto-table td {
            font-size: 10px;
            padding: 5px 6px;
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

        /* MOTTO Financial Summary */
        .financial-summary {
            border: 3px double #000;
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 6px 8px;
            font-weight: bold;
            border: 1px solid #000;
        }

        .summary-table .label {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        .summary-table .amount {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-size: 11px;
        }

        .total-row {
            border-top: 2px solid #000 !important;
            background-color: #e0e0e0 !important;
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
    <!-- MOTTO Background -->
    <div class="motto-background"></div>

    <!-- Content Overlay -->
    <div class="content-overlay">
    <!-- MOTTO Header -->
    <div class="motto-header">
        <div class="motto-title">ASSOCIATION OF FARMERS IN THE NORTHEAST OF NIGERIA</div>
        <div class="motto-subtitle">{{ strtoupper($tenantDisplayName) }} STATE CHAPTER</div>
        <div class="motto-description">Agricultural Input Support Program - Application Acknowledgement</div>
        <div class="reference-section">
            <div class="reference-label">Reference Number</div>
            <div class="reference-number">{{ $application->reference_number }}</div>
        </div>
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


    <!-- Commodities Section -->
    <div class="section-header">Commodities Allocation</div>
    <table class="motto-table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Commodity Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Unit Price (₦)</th>
                <th>Total Value (₦)</th>
            </tr>
        </thead>

            @foreach ($application->commodities as $index => $commodity)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $commodity->name }}</strong></td>
                <td class="text-right">{{ number_format($commodity->pivot->quantity) }}</td>
                <td class="text-center">{{ $commodity->unit }}</td>
                <td class="currency text-right">{{ number_format($commodity->price_per_unit, 2) }}</td>
                <td class="currency text-right"><strong>{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>


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
        <div class="summary-title">Financial Terms and Conditions</div>
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
                <td class="label">Base Commodity Value:</td>
                <td class="amount">₦{{ number_format($commodityTotal, 2) }}</td>
            </tr>

            @if($application->insurance_amount)
            <tr>
                <td class="label">Insurance Premium ({{ $application->insurance_rate ?? 0 }}%):</td>
                <td class="amount">₦{{ number_format($application->insurance_amount, 2) }}</td>
            </tr>
            @endif

            @if($application->equity)
            <tr>
                <td class="label">Equity Contributi(Held):</td>
                <td class="amount">₦{{ number_format($application->equity, 2) }}</td>
            </tr>
            @endif

            @if($application->total_loan)
            <tr class="total-row">
                <td class="label">TOTAL LOAN AMOUNT:</td>
                <td class="amount">₦{{ number_format($application->total_loan, 2) }}</td>
            </tr>
            @endif

            @if($application->disbursed_amount)
            <tr class="total-row">
                <td class="label">AMOUNT DISBURSED:</td>
                <td class="amount">₦{{ number_format($application->disbursed_amount, 2) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- QR Code Section -->
    <div class="qr-section">
        <div class="qr-title">Document Verification</div>
        <img src="data:image/svg+xml;base64,{!! base64_encode(
            QrCode::format('svg')->size(80)->backgroundColor(255,255,255)->color(0,0,0)->generate(url('/verify/'.$application->reference_number))
        ) !!}" alt="QR Code">
        <div class="qr-text">
            <strong>SCAN QR CODE FOR ONLINE VERIFICATION</strong><br>
            URL: {{ url('/verify/'.$application->reference_number) }}<br>
            This MOTTO document is digitally verifiable for authenticity
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
            MOTTO Generated: {{ now()->format('d/m/Y H:i') }} |
            Document ID: {{ $application->uuid }} |
            System: AFNON v1.0 |
            Page 1 of 1
        </div>
    </div>
    </div> <!-- End content-overlay -->
</body>
</html>
