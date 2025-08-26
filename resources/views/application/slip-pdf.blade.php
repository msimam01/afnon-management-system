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
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #0f172a;
            position: relative;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Modern Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-20deg);
            font-size: 140px;
            font-weight: 900;
            color: rgba(59, 130, 246, 0.03);
            text-align: center;
            z-index: -1;
            letter-spacing: 12px;
            font-family: 'Inter', sans-serif;
        }

        /* Ultra Modern Header */
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 25%, #2563eb 50%, #3b82f6 100%);
            color: white;
            padding: 30px 40px;
            margin: -15mm -15mm 25px -15mm;
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
            background:
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.08) 0%, transparent 50%);
        }

        .header-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 4px 8px rgba(0,0,0,0.2);
            letter-spacing: -0.5px;
        }

        .header-left .subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }

        .header-right {
            text-align: right;
        }

        .reference-badge {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 12px 20px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .verification-badge {
            background: #10b981;
            color: white;
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
        }

        /* Modern Section Headers */
        .section {
            margin: 30px 0;
        }

        .section-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 0 12px 12px 0;
            box-shadow: 0 2px 8px rgba(59,130,246,0.1);
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e40af;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Ultra Modern Table Design */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 11px;
        }

        .modern-table thead tr {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        }

        .modern-table thead th {
            color: white;
            padding: 16px 12px;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: left;
            border: none;
        }

        .modern-table thead th:first-child {
            padding-left: 20px;
            border-radius: 0;
        }

        .modern-table thead th:last-child {
            padding-right: 20px;
            border-radius: 0;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .modern-table tbody tr:hover {
            background: #e0f2fe;
            transform: translateY(-1px);
        }

        .modern-table tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .modern-table tbody td:first-child {
            padding-left: 20px;
            font-weight: 600;
            color: #1e40af;
        }

        .modern-table tbody td:last-child {
            padding-right: 20px;
            font-weight: 700;
            text-align: right;
        }

        /* Commodity Badge */
        .commodity-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #93c5fd;
            border-radius: 20px;
            font-weight: 600;
            color: #1e40af;
            font-size: 10px;
        }

        .commodity-icon {
            width: 16px;
            height: 16px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }

        /* Info Cards Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 25px 0;
        }

        .info-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .info-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e40af;
        }

        .card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-row:last-child {
            border-bottom: none;
        }

        .card-label {
            font-weight: 500;
            color: #64748b;
            font-size: 10px;
        }

        .card-value {
            font-weight: 700;
            color: #1e40af;
            font-size: 11px;
        }

        /* Financial Summary */
        .financial-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #3b82f6;
            border-radius: 20px;
            padding: 25px;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
        }

        .financial-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .summary-title {
            text-align: center;
            font-size: 16px;
            font-weight: 800;
            color: #1e40af;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .summary-item {
            text-align: center;
            background: white;
            padding: 18px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .summary-label {
            font-size: 9px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 800;
            color: #1e40af;
            font-family: 'Courier New', monospace;
        }

        /* QR Section */
        .qr-section {
            background: white;
            border: 2px dashed #3b82f6;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .qr-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .qr-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 15px;
            line-height: 1.6;
        }

        /* Signatures */
        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin: 40px 0;
        }

        .signature-block {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .signature-line {
            border-top: 2px solid #1e40af;
            margin: 35px auto 15px auto;
            width: 120px;
        }

        .signature-title {
            font-size: 11px;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .signature-subtitle {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            color: white;
            padding: 25px;
            margin: 30px -15mm -15mm -15mm;
            text-align: center;
            font-size: 10px;
            line-height: 1.6;
        }

        .footer-title {
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Utility Classes */
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 700;
        }

        .highlight {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            padding: 2px 6px;
            border-radius: 6px;
            font-weight: 600;
        }

        .status-approved {
            background: #10b981;
            color: white;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: 600;
        }

        .status-pending {
            background: #f59e0b;
            color: white;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Enhanced Watermark -->
    <div class="watermark">{{ strtoupper($tenantDisplayName) }}</div>

    <!-- Ultra Modern Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <h1>🌾 ACKNOWLEDGEMENT SLIP</h1>
                <p class="subtitle">{{ $tenantDisplayName }} Agricultural Finance Network</p>
            </div>
            <div class="header-right">
                <div class="reference-badge">REF: {{ $application->reference_number }}</div>
                <div class="verification-badge">✓ VERIFIED DOCUMENT</div>
            </div>
        </div>
    </div>

    <!-- Application Overview Cards -->
    <div class="info-grid">
        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">👤</div>
                <div class="card-title">Farmer Information</div>
            </div>
            <div class="card-row">
                <span class="card-label">Full Name</span>
                <span class="card-value">{{ $application->farmer->full_name }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Registration No.</span>
                <span class="card-value">{{ $application->farmer->registration_number }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Phone Number</span>
                <span class="card-value">{{ $application->farmer->phone }}</span>
            </div>
            @if($application->farmer->bvn)
            <div class="card-row">
                <span class="card-label">BVN (Masked)</span>
                <span class="card-value">{{ substr($application->farmer->bvn, 0, 3) }}****{{ substr($application->farmer->bvn, -3) }}</span>
            </div>
            @endif
        </div>

        <div class="info-card">
            <div class="card-header">
                <div class="card-icon">🚜</div>
                <div class="card-title">Farm & Season Details</div>
            </div>
            <div class="card-row">
                <span class="card-label">Current Season</span>
                <span class="card-value">{{ $application->season->name }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Farm Size</span>
                <span class="card-value">{{ $application->farm->size }} hectares</span>
            </div>
            <div class="card-row">
                <span class="card-label">Application Date</span>
                <span class="card-value">{{ $application->created_at->format('d M, Y') }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Generated On</span>
                <span class="card-value">{{ now()->format('d M, Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Commodities Section -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">
                🌾 Commodities & Financial Breakdown
            </div>
            <div class="section-subtitle">Detailed breakdown of requested commodities and associated costs</div>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Commodity Details</th>
                        <th>Quantity Requested</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($application->commodities as $commodity)
                    <tr>
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon">{{ substr($commodity->name, 0, 1) }}</div>
                                {{ $commodity->name }}
                            </div>
                        </td>
                        <td>{{ number_format($commodity->pivot->quantity) }} <span class="highlight">{{ $commodity->unit }}</span></td>
                        <td class="currency">₦{{ number_format($commodity->price_per_unit, 2) }}</td>
                        <td class="currency">₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="financial-summary">
        <div class="summary-title">💰 Financial Summary Overview</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">🛡️ Insurance ({{ $application->insurance_rate }}%)</div>
                <div class="summary-value currency">₦{{ number_format($application->insurance_amount, 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">💳 Total Loan</div>
                <div class="summary-value currency">₦{{ number_format($application->total_loan, 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">🏦 Equity Held</div>
                <div class="summary-value currency">₦{{ number_format($application->equity, 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">💸 Disbursed</div>
                <div class="summary-value currency">₦{{ number_format($application->disbursed_amount, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Enhanced Commodity Allocation Breakdown -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">📦 Detailed Commodity Allocation</div>
            <div class="section-subtitle">Comprehensive breakdown showing allocation details per hectare</div>
        </div>

        <div class="table-container">
            @if($application->commodity_allocations && $application->commodity_allocations->count() > 0)
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Commodity</th>
                        <th>Qty/Hectare</th>
                        <th>Farm Size</th>
                        <th>Allocated Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($application->commodity_allocations as $allocation)
                    <tr>
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon">{{ substr($allocation->commodity_name, 0, 1) }}</div>
                                {{ $allocation->commodity_name }}
                            </div>
                        </td>
                        <td>{{ $allocation->qty_per_hectare }}</td>
                        <td>{{ $application->farm->size }} <span class="highlight">ha</span></td>
                        <td><span class="status-approved">{{ $allocation->allocated_quantity }}</span></td>
                        <td class="currency">₦{{ number_format($allocation->unit_price, 2) }}</td>
                        <td class="currency">₦{{ number_format($allocation->total_value, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Commodity</th>
                        <th>Qty/Hectare</th>
                        <th>Farm Size</th>
                        <th>Requested Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($application->commodities as $commodity)
                    @php
                        $requestedQty = $commodity->pivot->quantity ?? 0;
                        $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
                        $unitPrice = $commodity->price_per_unit ?? 0;
                        $totalValue = $requestedQty * $unitPrice;
                    @endphp
                    <tr>
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon">{{ substr($commodity->name, 0, 1) }}</div>
                                {{ $commodity->name }}
                            </div>
                        </td>
                        <td>{{ $qtyPerHectare }}</td>
                        <td>{{ $application->farm->size }} <span class="highlight">ha</span></td>
                        <td><span class="status-pending">{{ $requestedQty }}</span></td>
                        <td class="currency">₦{{ number_format($unitPrice, 2) }}</td>
                        <td class="currency">₦{{ number_format($totalValue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    <!-- Loan Summary Table -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">💰 Complete Loan Breakdown & Summary</div>
            <div class="section-subtitle">Detailed financial structure with all components and calculations</div>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Financial Component</th>
                        <th>Rate/Percentage</th>
                        <th>Amount (₦)</th>
                    </tr>
                </thead>
                <tbody>
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
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon" style="background: #10b981;">C</div>
                                Base Commodity Value
                            </div>
                        </td>
                        <td><span class="highlight">Base Amount</span></td>
                        <td class="currency">{{ number_format($commodityTotal, 2) }}</td>
                    </tr>

                    @if($application->insurance_amount)
                    <tr>
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon" style="background: #f97316;">I</div>
                                Insurance Premium
                            </div>
                        </td>
                        <td><span class="status-approved">{{ $application->insurance_rate ?? 0 }}%</span></td>
                        <td class="currency">{{ number_format($application->insurance_amount, 2) }}</td>
                    </tr>
                    @endif

                    @if($application->equity)
                    <tr>
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon" style="background: #a855f7;">E</div>
                                Equity Contribution (Held)
                            </div>
                        </td>
                        <td><span class="highlight">Farmer's Share</span></td>
                        <td class="currency">{{ number_format($application->equity, 2) }}</td>
                    </tr>
                    @endif

                    @if($application->total_loan)
                    <tr style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); font-weight: bold;">
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon" style="background: #3b82f6; width: 20px; height: 20px;">T</div>
                                <strong>TOTAL LOAN AMOUNT</strong>
                            </div>
                        </td>
                        <td><span class="status-approved">Final Amount</span></td>
                        <td class="currency" style="font-size: 14px; color: #1e40af;">{{ number_format($application->total_loan, 2) }}</td>
                    </tr>
                    @endif

                    @if($application->disbursed_amount)
                    <tr style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); font-weight: bold;">
                        <td>
                            <div class="commodity-badge">
                                <div class="commodity-icon" style="background: #10b981; width: 20px; height: 20px;">D</div>
                                <strong>AMOUNT DISBURSED</strong>
                            </div>
                        </td>
                        <td><span class="status-approved">Actually Paid</span></td>
                        <td class="currency" style="font-size: 14px; color: #059669;">{{ number_format($application->disbursed_amount, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enhanced QR Code Section -->
    <div class="qr-section">
        <div class="qr-title">📱 Instant Digital Verification</div>
        <img src="data:image/svg+xml;base64,{!! base64_encode(
            QrCode::format('svg')->size(100)->backgroundColor(255,255,255)->generate(url('/verify/'.$application->reference_number))
        ) !!}" alt="QR Code">
        <div class="qr-subtitle">
            <strong>Scan with your smartphone for instant online verification</strong><br>
            Verification URL: <span class="highlight">{{ url('/verify/'.$application->reference_number) }}</span><br>
            This QR code provides secure access to verify the authenticity of this document
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
            <div class="signature-title">Date & Timestamp</div>
            <div class="signature-subtitle">{{ now()->format('d/m/Y H:i:s') }}</div>
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
