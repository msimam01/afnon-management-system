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
            margin: 15mm;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #1f2937;
            position: relative;
            margin: 0;
            padding: 0;
        }

        /* Enhanced Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 120px;
            font-weight: bold;
            color: rgba(16, 185, 129, 0.08);
            text-align: center;
            z-index: -1;
            letter-spacing: 8px;
        }

        /* Modern Header */
        .header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 25px;
            margin: -15mm -15mm 20px -15mm;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0.3;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin: 0;
            font-weight: 300;
        }

        .header .reference {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
            backdrop-filter: blur(10px);
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        /* Enhanced Details Section */
        .details-section {
            margin: 25px 0;
        }

        .section-title {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 12px 20px;
            margin: 0 0 15px 0;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .details-grid {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-card {
            flex: 1;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .detail-card h3 {
            margin: 0 0 10px 0;
            color: #059669;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-item {
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail-label {
            font-weight: 600;
            color: #374151;
            font-size: 11px;
        }

        .detail-value {
            font-weight: bold;
            color: #1f2937;
            font-size: 11px;
        }

        /* Enhanced Table */
        .table-container {
            margin: 20px 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #f0fdf4;
        }

        .commodity-name {
            font-weight: bold;
            color: #059669;
        }

        .amount {
            font-weight: bold;
            color: #1f2937;
        }

        /* Financial Summary */
        .financial-summary {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 15px;
            margin: 20px 0;
        }

        .summary-grid {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .summary-item {
            text-align: center;
            flex: 1;
        }

        .summary-label {
            font-size: 10px;
            color: #059669;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
        }

        /* Enhanced Footer */
        .footer {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .footer-content {
            text-align: center;
        }

        .footer-title {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-text {
            font-size: 10px;
            color: #6b7280;
            line-height: 1.4;
        }

        /* Signatures Section */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            gap: 20px;
        }

        .signature-block {
            flex: 1;
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
        }

        .signature-line {
            border-top: 2px solid #374151;
            margin: 30px auto 10px auto;
            width: 150px;
        }

        .signature-title {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .signature-subtitle {
            font-size: 9px;
            color: #6b7280;
            margin-top: 5px;
        }
        }

        /* QR Code Section */
        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: white;
            border: 2px dashed #10b981;
            border-radius: 12px;
        }

        .qr-title {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .qr-subtitle {
            font-size: 10px;
            color: #6b7280;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Enhanced Watermark -->
    <div class="watermark">{{ strtoupper($tenantDisplayName) }} AFNON</div>

    <!-- Status Badge -->
    <div class="status-badge">✓ VERIFIED</div>

    <!-- Enhanced Header -->
    <div class="header">
        <div class="header-content">
            <h1>🌾 ACKNOWLEDGEMENT SLIP</h1>
            <p class="subtitle">{{ $tenantDisplayName }} Agricultural Finance Network</p>
            <div class="reference">REF: {{ $application->reference_number }}</div>
        </div>
    </div>

    <!-- Application Details Section -->
    <div class="details-section">
        <div class="section-title">📋 APPLICATION DETAILS</div>

        <div class="details-grid">
            <!-- Farmer Information -->
            <div class="detail-card">
                <h3>👤 Farmer Information</h3>
                <div class="detail-item">
                    <span class="detail-label">Full Name:</span>
                    <span class="detail-value">{{ $application->farmer->full_name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Registration No:</span>
                    <span class="detail-value">{{ $application->farmer->registration_number }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone Number:</span>
                    <span class="detail-value">{{ $application->farmer->phone }}</span>
                </div>
                @if($application->farmer->bvn)
                <div class="detail-item">
                    <span class="detail-label">BVN:</span>
                    <span class="detail-value">{{ substr($application->farmer->bvn, 0, 3) }}****{{ substr($application->farmer->bvn, -3) }}</span>
                </div>
                @endif
            </div>

            <!-- Farm & Season Information -->
            <div class="detail-card">
                <h3>🚜 Farm & Season Details</h3>
                <div class="detail-item">
                    <span class="detail-label">Season:</span>
                    <span class="detail-value">{{ $application->season->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Farm Size:</span>
                    <span class="detail-value">{{ $application->farm->size }} hectares</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Application Date:</span>
                    <span class="detail-value">{{ $application->created_at->format('d M, Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Generated:</span>
                    <span class="detail-value">{{ now()->format('d M, Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Commodities Section -->
    <div class="details-section">
        <div class="section-title">🌾 COMMODITIES & FINANCIAL BREAKDOWN</div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>🌱 Commodity</th>
                        <th>📊 Quantity</th>
                        <th>💰 Unit Price</th>
                        <th>💵 Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($application->commodities as $commodity)
                        <tr>
                            <td class="commodity-name">{{ $commodity->name }}</td>
                            <td>{{ number_format($commodity->pivot->quantity) }} {{ $commodity->unit }}</td>
                            <td class="amount">₦{{ number_format($commodity->price_per_unit, 2) }}</td>
                            <td class="amount">₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Financial Summary -->
        <div class="financial-summary">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-label">🛡️ Insurance ({{ $application->insurance_rate }}%)</div>
                    <div class="summary-value">₦{{ number_format($application->insurance_amount, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">💳 Total Loan</div>
                    <div class="summary-value">₦{{ number_format($application->total_loan, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">🏦 Equity Held</div>
                    <div class="summary-value">₦{{ number_format($application->equity, 2) }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">💸 Disbursed Amount</div>
                    <div class="summary-value">₦{{ number_format($application->disbursed_amount, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced QR Code Section -->
    <div class="qr-section">
        <div class="qr-title">📱 INSTANT VERIFICATION</div>
        <img src="data:image/svg+xml;base64,{!! base64_encode(
            QrCode::format('svg')->size(100)->backgroundColor(255,255,255)->generate(url('/verify/'.$application->reference_number))
        ) !!}" alt="QR Code">
        <div class="qr-subtitle">
            Scan this QR code with your smartphone to instantly verify this application online<br>
            <strong>Verification URL:</strong> {{ url('/verify/'.$application->reference_number) }}
        </div>
    </div>

    <!-- Enhanced Signatures Section -->
    <div class="signatures">
        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-title">Official Seal</div>
            <div class="signature-subtitle">{{ $tenantDisplayName }} AFNON Office</div>
        </div>

        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-title">Authorized Officer</div>
            <div class="signature-subtitle">Loan Processing Department</div>
        </div>

        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-title">Date & Time</div>
            <div class="signature-subtitle">{{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <!-- Enhanced Footer -->
    <div class="footer">
        <div class="footer-content">
            <div class="footer-title">🌾 {{ strtoupper($tenantDisplayName) }} AGRICULTURAL FINANCE NETWORK</div>
            <div class="footer-text">
                <strong>Document Generated:</strong> {{ now()->format('l, F j, Y \a\t g:i A') }}<br>
                <strong>System:</strong> AFNON Management System v2.0 | <strong>Document ID:</strong> {{ $application->uuid }}<br>
                <strong>Security:</strong> This document is digitally secured and can be verified online using the QR code above<br>
                <strong>Contact:</strong> support@afnon.com | <strong>Website:</strong> www.afnon.com
            </div>
        </div>
    </div>
</body>
</html>
