@php
    // Tenant short code (first & last two letters)
    $tenantName = tenant()->id ?? 'Tenant';
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
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            position: relative;
        }
        /* Watermark */
        .watermark {
            position: fixed;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            text-align: center;
            z-index: -1;
        }
        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #059669;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header img {
            height: 70px;
            margin-bottom: 5px;
        }
        .header h1 {
            margin: 0;
            color: #065f46;
        }
        /* Details */
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card {
            width: 48%;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
        }
        .card p {
            margin: 4px 0;
        }
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
        }
        tfoot td {
            font-weight: bold;
            background: #f9fafb;
        }
        /* Footer */
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 25px;
            color: #6b7280;
        }
        /* Seal & Signature */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 12px;
        }
        .signature-block {
            text-align: center;
        }
        .seal {
            width: 100px;
            opacity: 0.2;
        }
        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
        }
        /* QR */
        .qr {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">AFNON</div>

    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/afnon-logo.png') }}" alt="AFNON Logo">
        <h1>Acknowledgement Slip</h1>
        <p>Reference: <strong>{{ $application->reference_number }}</strong></p>
    </div>

    <!-- Details -->
    <div class="details">
        <div class="card">
            <p><strong>Farmer:</strong> {{ $application->farmer->full_name }}</p>
            <p><strong>Reg. No.:</strong> {{ $application->farmer->registration_number }}</p>
            <p><strong>Phone:</strong> {{ $application->farmer->phone }}</p>
        </div>
        <div class="card">
            <p><strong>Season:</strong> {{ $application->season->name }}</p>
            <p><strong>Farm Size:</strong> {{ $application->farm->size }} ha</p>
            <p><strong>Date:</strong> {{ now()->format('d M, Y') }}</p>
        </div>
    </div>

    <!-- Commodities -->
    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($application->commodities as $commodity)
                <tr>
                    <td>{{ $commodity->name }}</td>
                    <td>{{ number_format($commodity->pivot->quantity) }} {{ $commodity->unit }}</td>
                    <td>₦{{ number_format($commodity->price_per_unit, 2) }}</td>
                    <td>₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Insurance ({{ $application->insurance_rate }}%)</td>
                <td>₦{{ number_format($application->insurance_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3">Total Loan</td>
                <td>₦{{ number_format($application->total_loan, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3">Equity Held</td>
                <td>₦{{ number_format($application->equity, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3">Disbursed Amount</td>
                <td>₦{{ number_format($application->disbursed_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- QR Code -->
    <div class="qr">
        <img src="data:image/svg+xml;base64,{!! base64_encode(
            QrCode::format('svg')->size(120)->generate(route('applications.verify', $application->reference_number)),
        ) !!}">
        <p>Scan to verify application</p>
    </div>

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature-block">
            <img src="{{ public_path('images/seal.png') }}" alt="Seal" class="seal">
            <div class="signature-line"></div>
            <p>Official Seal</p>
        </div>
        <div class="signature-block">
            <div class="signature-line"></div>
            <p>Authorized Signature</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        Generated on {{ now()->format('d M, Y H:i') }} | AFNON Loan System
    </div>
</body>
</html>
