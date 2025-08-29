@php
    // Tenant information
    $tenantName = tenant()->id ?? 'Tenant';
    $tenantDisplayName = ucfirst($tenantName);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement Slip - {{ $application->reference_number }}</title>
    @include('application.includes.app')
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.5;
            color: #374151;
            background: #f9fafb;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        /* Header */
        .header {
            background: #111827;
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header .subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .reference {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Content */
        .content {
            padding: 2rem;
        }

        .section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.5rem;
        }

        .info-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: #6b7280;
        }

        .info-value {
            font-weight: 600;
            color: #111827;
        }

        /* Tables */
        .table-container {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #f3f4f6;
        }

        tr:hover {
            background: #f9fafb;
        }

        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        /* Financial Summary */
        .financial-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .summary-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0c4a6e;
            text-align: center;
            margin-bottom: 1rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .summary-item {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            text-align: center;
            border: 1px solid #e0f2fe;
        }

        .summary-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .summary-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0c4a6e;
            font-family: 'Courier New', monospace;
        }

        /* QR Section */
        .qr-section {
            text-align: center;
            padding: 1.5rem;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .qr-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #374151;
        }

        .qr-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 1rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            padding: 2rem 0;
            border-top: 1px solid #e5e7eb;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            border: 1px solid #2563eb;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
                border-radius: 0;
            }
            .action-buttons,
            .qr-section {
                display: none;
            }
            .header {
                background: #111827 !important;
                color: white !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Acknowledgement Slip</h1>
            <p class="subtitle">{{ $tenantDisplayName }} Agricultural Finance Network</p>
            <div class="reference">REF: {{ $application->reference_number }}</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Application Information -->
            <div class="section">
                <h2 class="section-title">Application Information</h2>
                
                <div class="info-grid">
                    <div class="info-card">
                        <h3>Farmer Details</h3>
                        <div class="info-row">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ $application->farmer->full_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Registration No.</span>
                            <span class="info-value">{{ $application->farmer->registration_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone Number</span>
                            <span class="info-value">{{ $application->farmer->phone }}</span>
                        </div>
                        @if($application->farmer->bvn)
                        <div class="info-row">
                            <span class="info-label">BVN (Masked)</span>
                            <span class="info-value">{{ substr($application->farmer->bvn, 0, 3) }}****{{ substr($application->farmer->bvn, -3) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="info-card">
                        <h3>Farm & Season</h3>
                        <div class="info-row">
                            <span class="info-label">Season</span>
                            <span class="info-value">{{ $application->season->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Farm Size</span>
                            <span class="info-value">{{ $application->farm->size }} hectares</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Application Date</span>
                            <span class="info-value">{{ $application->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Generated</span>
                            <span class="info-value">{{ now()->format('M d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commodities -->
            <div class="section">
                <h2 class="section-title">Commodities Requested</h2>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Commodity</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($application->commodities as $commodity)
                            <tr>
                                <td>
                                    <strong>{{ $commodity->name }}</strong>
                                    <br><small class="text-gray-500">{{ $commodity->unit }}</small>
                                </td>
                                <td>{{ number_format($commodity->pivot->quantity) }}</td>
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
                <div class="summary-title">Financial Summary</div>
                <div class="summary-grid">
                    @if($application->insurance_amount)
                    <div class="summary-item">
                        <div class="summary-label">Insurance ({{ $application->insurance_rate }}%)</div>
                        <div class="summary-value">₦{{ number_format($application->insurance_amount, 2) }}</div>
                    </div>
                    @endif
                    
                    @if($application->total_loan)
                    <div class="summary-item">
                        <div class="summary-label">Total Loan</div>
                        <div class="summary-value">₦{{ number_format($application->total_loan, 2) }}</div>
                    </div>
                    @endif
                    
                    @if($application->equity)
                    <div class="summary-item">
                        <div class="summary-label">Equity Held</div>
                        <div class="summary-value">₦{{ number_format($application->equity, 2) }}</div>
                    </div>
                    @endif
                    
                    @if($application->disbursed_amount)
                    <div class="summary-item">
                        <div class="summary-label">Disbursed</div>
                        <div class="summary-value">₦{{ number_format($application->disbursed_amount, 2) }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- QR Verification -->
            <div class="qr-section">
                <div class="qr-title">Document Verification</div>
                {!! QrCode::size(100)->backgroundColor(255,255,255)
                    ->generate(url('/verify/'.$application->reference_number)) !!}
                <p class="qr-subtitle">
                    Scan to verify document authenticity<br>
                    Verification URL: {{ url('/verify/'.$application->reference_number) }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i>
                    Print Document
                </button>
                
                <a href="{{ route('applications.slip.pdf', $application->uuid) }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    Download PDF
                </a>
                
                <a href="{{ route('applications.verify', $application->reference_number) }}" class="btn btn-secondary">
                    <i class="fas fa-external-link-alt"></i>
                    Verify Online
                </a>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div style="text-align: center; padding: 1rem; color: #6b7280; font-size: 0.875rem;">
        <p>{{ $tenantDisplayName }} Agricultural Finance Network</p>
        <p>Document ID: {{ $application->uuid }} | Generated: {{ now()->format('M d, Y H:i A') }}</p>
    </div>

    {!! ToastMagic::scripts() !!}
</body>
</html>