
@php
    // Tenant information
    $tenantName = tenant()->id ?? 'Tenant';
    $tenantDisplayName = ucfirst($tenantName);
@endphp

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement Slip - {{ $application->reference_number }}</title>
    @include('application.includes.app')
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'system-ui', 'sans-serif'],
                },
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'scale-in': 'scaleIn 0.5s ease-out',
                        'bounce-soft': 'bounceSoft 2s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.9)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        bounceSoft: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(16, 185, 129, 0.3)' },
                            '50%': { boxShadow: '0 0 40px rgba(16, 185, 129, 0.6)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdf4 100%);
            min-height: 100vh;
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
            opacity: 0.08;
            background-image: url('{{ asset('images/motto-background.jpg') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
        }

        /* Enhanced Container */
        .container {
            max-width: 900px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(3px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Enhanced Header */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            color: white;
            padding: 3rem 2rem;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .reference {
            display: inline-block;
            background: rgba(16, 185, 129, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            border: 2px solid rgba(16, 185, 129, 0.3);
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            animation: pulse-glow 3s ease-in-out infinite;
        }

        /* Enhanced Content */
        .content {
            padding: 3rem 2rem;
        }

        .section {
            margin-bottom: 3rem;
            animation: slide-up 0.8s ease-out;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #10b981;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 2px;
        }

        /* Enhanced Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669, #0ea5e9);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -12px rgba(16, 185, 129, 0.25);
            border-color: #10b981;
        }

        .info-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card h3::before {
            content: '';
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row:hover {
            background: rgba(16, 185, 129, 0.05);
            margin: 0 -1rem;
            padding-left: 1rem;
            padding-right: 1rem;
            border-radius: 8px;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 700;
            color: #0f172a;
            font-size: 1rem;
        }

        /* Enhanced Tables */
        .table-container {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1.25rem 1rem;
            text-align: left;
            font-weight: 700;
            color: #374151;
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f8fafc;
            transition: all 0.2s ease;
        }

        tr:hover {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        }

        .currency {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-weight: 700;
            color: #059669;
        }

        /* Enhanced Financial Summary */
        .financial-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #f0fdf4 100%);
            border: 2px solid #0ea5e9;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .financial-summary::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            animation: bounce-soft 4s ease-in-out infinite;
        }

        .summary-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0c4a6e;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .summary-item {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 16px;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .summary-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .summary-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.3);
        }

        .summary-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0c4a6e;
            font-family: 'JetBrains Mono', 'Courier New', monospace;
        }

        /* Enhanced QR Section */
        .qr-section {
            text-align: center;
            padding: 2.5rem;
            background: linear-gradient(135deg, #fafafa 0%, #f4f4f5 100%);
            border: 2px dashed #d1d5db;
            border-radius: 20px;
            margin: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .qr-section::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
        }

        .qr-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            color: #374151;
            position: relative;
            z-index: 2;
        }

        .qr-code {
            display: inline-block;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .qr-subtitle {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 1rem;
            position: relative;
            z-index: 2;
        }

        /* Enhanced Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            padding: 3rem 0 2rem;
            border-top: 2px solid #f1f5f9;
            margin-top: 3rem;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            border: 2px solid #2563eb;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(37, 99, 235, 0.6);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            color: #374151;
            border: 2px solid #d1d5db;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.15);
            border-color: #10b981;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: 2px solid #10b981;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.6);
        }

        /* Success Badge */
        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            animation: pulse-glow 2s ease-in-out infinite;
        }

        /* Footer Enhancement */
        .footer-info {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #64748b;
            font-size: 0.9rem;
            border-top: 1px solid #e2e8f0;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
            }
            .action-buttons,
            .qr-section {
                display: none;
            }
            .header {
                background: #0f172a !important;
                color: white !important;
            }
            .financial-summary::before,
            .qr-section::before {
                display: none;
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
                border-radius: 16px;
            }

            .header {
                padding: 2rem 1rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content {
                padding: 2rem 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>

<body class="animate-fade-in">
    <!-- MOTTO Background -->
    <div class="motto-background"></div>

    <div class="container animate-scale-in">
        <!-- Enhanced Header -->
        <div class="header">
            <div class="header-content">
                <div class="success-badge">
                    <i class="fas fa-check-circle"></i>
                    Application Submitted Successfully
                </div>
                <h1>ASSOCIATION OF FARMERS IN THE NORTHEAST OF NIGERIA</h1>
                <p class="subtitle">{{ strtoupper($tenantDisplayName) }} STATE CHAPTER</p>
                <p class="subtitle" style="font-size: 0.9rem; font-style: italic; margin-top: 0.5rem;">Agricultural Input Support Program - Application Acknowledgement</p>
                <div class="reference">REF: {{ $application->reference_number }}</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Application Information -->
            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Application Information
                </h2>

                <div class="info-grid">
                    <div class="info-card">
                        <h3>
                            <i class="fas fa-user text-emerald-600"></i>
                            Farmer Details
                        </h3>
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
                        <h3>
                            <i class="fas fa-tractor text-emerald-600"></i>
                            Farm & Season
                        </h3>
                        <div class="info-row">
                            <span class="info-label">Season</span>
                            <span class="info-value">{{ $application->season->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Farm Size</span>
                            <span class="info-value">{{ $application->farm->size }} hectares</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Farm Location</span>
                            <span class="info-value">{{ $application->farm->location }}</span>
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
                <h2 class="section-title">
                    <i class="fas fa-seedling text-green-600 mr-2"></i>
                    Commodities Allocation
                </h2>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th><i class="fas fa-box mr-2"></i>Commodity</th>
                                <th><i class="fas fa-weight mr-2"></i>Quantity</th>
                                <th><i class="fas fa-tag mr-2"></i>Unit Price</th>
                                <th><i class="fas fa-calculator mr-2"></i>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($application->commodities as $commodity)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-emerald-500 rounded-full mr-3"></div>
                                        <div>
                                            <strong>{{ $commodity->name }}</strong>
                                            <br><small class="text-gray-500">{{ $commodity->unit }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="font-semibold">{{ number_format($commodity->pivot->quantity) }}</td>
                                <td class="currency">₦{{ number_format($commodity->price_per_unit, 2) }}</td>
                                <td class="currency">₦{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Enhanced Financial Summary -->
            <div class="financial-summary">
                <div class="summary-title">
                    <i class="fas fa-chart-line mr-3"></i>
                    Financial Terms and Conditions
                </div>
                <div class="summary-grid">
                    @if($application->insurance_amount)
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Insurance ({{ $application->insurance_rate }}%)
                        </div>
                        <div class="summary-value">₦{{ number_format($application->insurance_amount, 2) }}</div>
                    </div>
                    @endif

                    @if($application->total_loan)
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            Total Loan
                        </div>
                        <div class="summary-value">₦{{ number_format($application->total_loan, 2) }}</div>
                    </div>
                    @endif

                    @if($application->equity)
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-piggy-bank mr-1"></i>
                            Equity Held
                        </div>
                        <div class="summary-value">₦{{ number_format($application->equity, 2) }}</div>
                    </div>
                    @endif

                    @if($application->disbursed_amount)
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-hand-holding-usd mr-1"></i>
                            You Receive
                        </div>
                        <div class="summary-value">₦{{ number_format($application->disbursed_amount, 2) }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced QR Verification -->
            <div class="qr-section">
                <div class="qr-title">
                    <i class="fas fa-qrcode mr-2"></i>
                    MOTTO Document Verification
                </div>
                <div class="qr-code">
                    {!! QrCode::size(120)->backgroundColor(255,255,255)
                        ->generate(url('/verify/'.$application->reference_number)) !!}
                </div>
                <p class="qr-subtitle">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    Scan to verify document authenticity<br>
                    <strong>Verification URL:</strong> {{ url('/verify/'.$application->reference_number) }}
                </p>
            </div>

            <!-- Enhanced Action Buttons -->
            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i>
                    Print Document
                </button>

                <a href="{{ route('applications.slip.pdf', $application->uuid) }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    Download PDF
                </a>

                <a href="{{ route('applications.verify', $application->reference_number) }}" class="btn btn-success">
                    <i class="fas fa-external-link-alt"></i>
                    Verify Online
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Footer Info -->
    <div class="footer-info">
        <div class="flex flex-col items-center space-y-2">
            <div class="flex items-center space-x-2">
                <i class="fas fa-seedling text-emerald-600"></i>
                <span class="font-semibold">{{ $tenantDisplayName }} Agricultural Finance Network</span>
            </div>
            <div class="text-sm">
                <span class="font-medium">MOTTO Document ID:</span> {{ $application->uuid }} |
                <span class="font-medium">Generated:</span> {{ now()->format('M d, Y H:i A') }} |
                <span class="font-medium">Page:</span> 1 of 1
            </div>
            <div class="text-xs text-gray-500 mt-2">
                Keep this acknowledgement slip safe for your records. You will need the reference number for future inquiries.
            </div>
        </div>
    </div>

    <script>
        // Add some interactive enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add stagger animation to info cards
            const infoCards = document.querySelectorAll('.info-card');
            infoCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.2}s`;
                card.classList.add('animate-slide-up');
            });

            // Add stagger animation to summary items
            const summaryItems = document.querySelectorAll('.summary-item');
            summaryItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('animate-slide-up');
            });

            // Add copy functionality for reference number
            const referenceElement = document.querySelector('.reference');
            if (referenceElement) {
                referenceElement.style.cursor = 'pointer';
                referenceElement.title = 'Click to copy reference number';

                referenceElement.addEventListener('click', function() {
                    const refNumber = '{{ $application->reference_number }}';
                    navigator.clipboard.writeText(refNumber).then(function() {
                        // Show success feedback
                        const originalText = referenceElement.textContent;
                        referenceElement.textContent = 'Copied!';
                        referenceElement.style.background = 'rgba(16, 185, 129, 0.3)';

                        setTimeout(() => {
                            referenceElement.textContent = originalText;
                            referenceElement.style.background = 'rgba(16, 185, 129, 0.2)';
                        }, 2000);
                    });
                });
            }

            // Add smooth scroll for better UX
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add print preparation
            window.addEventListener('beforeprint', function() {
                document.body.classList.add('printing');
            });

            window.addEventListener('afterprint', function() {
                document.body.classList.remove('printing');
            });
        });
    </script>

    {!! ToastMagic::scripts() !!}
</body>
</html>
