@php
    // Tenant information
    $tenantName = tenant()->id ?? 'Tenant';
    $tenantDisplayName = ucfirst($tenantName);
    
    // Calculate total value of commodities
    $totalValue = 0;
    foreach ($application->commodities as $commodity) {
        $totalValue += $commodity->pivot->quantity * $commodity->price_per_unit;
    }
    
    // Current date
    $currentDate = now()->format('d F, Y');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACKNOWLEDGMENT SLIP - {{ $application->reference_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm 15mm 20mm;
            
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9px;
                color: #999;
            }
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            font-size: 12px;
            max-width: 210mm;
            border: 1px solid #e2e8f0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            background: white;
        }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            opacity: 0.05;
            pointer-events: none;
            white-space: nowrap;
            z-index: -1;
            font-weight: bold;
            color: #999;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a365d;
        }
        
        .header p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #4a5568;
        }
        
        .reference {
            position: absolute;
            top: 0;
            right: 0;
            background: #1a365d;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .date {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 11px;
            color: #4a5568;
        }
        
        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px;
            font-size: 11px;
            page-break-inside: avoid;
            border: 1px solid #e2e8f0;
        }
        
        th {
            background-color: #1a365d;
            color: white;
            font-weight: 600;
            padding: 8px 10px;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        /* Sections */
        .section-title {
            background-color: #edf2f7;
            padding: 6px 10px;
            font-weight: 600;
            color: #2d3748;
            margin: 20px 0 10px;
            border-left: 4px solid #4299e1;
            font-size: 13px;
        }
        
        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            padding: 10px 15mm;
            font-size: 9px;
            color: #718096;
            background-color: white;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        
        /* Utilities */
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-bold {
            font-weight: bold;
        }
        
        .mb-4 {
            margin-bottom: 1rem;
        }
        
        .mt-6 {
            margin-top: 1.5rem;
        }
        
        .signature-line {
            border-top: 1px solid #cbd5e0;
            margin: 30px 0 10px;
            padding-top: 5px;
            font-size: 11px;
            color: #4a5568;
        }
        
        /* Print specific styles */
        @media print {
            body {
                padding: 20px;
                font-size: 11px;
                margin: 0 auto;
                border: none;
                box-shadow: none;
            }
            
            .no-print {
                display: none;
            }
            
            .footer {
                position: fixed;
                bottom: 0;
            }
            
            table {
                page-break-inside: avoid;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
    <style>
        /* Additional styles for better printing */
        @media print {
            @page { margin: 20mm 15mm 20mm 15mm; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
    {!! ToastMagic::styles() !!}
</head>

<body>
    <!-- Watermark -->
    <div class="watermark">{{ strtoupper($tenantDisplayName) }} STATE</div>
    
    <div class="header">
        <div class="date">Date: {{ $currentDate }}</div>
        <h1>ACKNOWLEDGMENT SLIP</h1>
        <p>AGRICULTURAL INPUT SUPPORT PROGRAM</p>
        <p style="font-weight: 600; color: #1a365d;">{{ strtoupper($tenantDisplayName) }} STATE CHAPTER</p>
        <div class="reference">REF: {{ $application->reference_number }}</div>
    </div>

    <!-- Application Information -->
    <div class="section-title">1. APPLICANT INFORMATION</div>
    <table>
        <colgroup>
            <col style="width: 20%;">
            <col style="width: 30%;">
            <col style="width: 20%;">
            <col style="width: 30%;">
        </colgroup>
        <tr>
            <td width="20%"><strong>Name:</strong></td>
            <td width="30%">{{ $application->farmer->full_name ?? 'N/A' }}</td>
            <td width="20%"><strong>Phone:</strong></td>
            <td width="30%">{{ $application->farmer->phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Registration No.:</strong></td>
            <td>{{ $application->farmer->registration_number ?? 'N/A' }}</td>
            <td><strong>BVN (Masked):</strong></td>
            <td>@if($application->farmer->bvn) {{ substr($application->farmer->bvn, 0, 3) }}****{{ substr($application->farmer->bvn, -3) }} @else N/A @endif</td>
        </tr>
        <tr>
            <td><strong>Season:</strong></td>
            <td>{{ $application->season->name ?? 'N/A' }}</td>
            <td><strong>Farm Size:</strong></td>
            <td>{{ $application->farm_size ?? ($application->farm->size ?? 'N/A') }} hectares</td>
        </tr>
        <tr>
            <td><strong>Farm Location:</strong></td>
            <td colspan="3">{{ $application->farm_location ?? ($application->farm->location ?? 'N/A') }}</td>
        </tr>
        <tr>
            <td><strong>Application Date:</strong></td>
            <td colspan="3">{{ $application->created_at->format('d M, Y') }}</td>
        </tr>
    </table>

    <!-- Commodities Allocation -->
    <div class="section-title">2. COMMODITIES ALLOCATION</div>
    <table>
        <colgroup>
            <col style="width: 45%;">
            <col style="width: 15%;">
            <col style="width: 20%;">
            <col style="width: 20%;">
        </colgroup>
        <thead>
            <tr>
                <th>Commodity</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Unit Price (₦)</th>
                <th class="text-right">Total (₦)</th>
            </tr>
        </thead>
        @foreach ($application->commodities as $commodity)
        <tr>
            <td>{{ $commodity->name }} ({{ $commodity->unit }})</td>
            <td class="text-center">{{ number_format($commodity->pivot->quantity) }}</td>
            <td class="text-right">{{ number_format($commodity->price_per_unit, 2) }}</td>
            <td class="text-right">{{ number_format($commodity->pivot->quantity * $commodity->price_per_unit, 2) }}</td>
        </tr>
        @endforeach
        <tr class="text-bold">
            <td colspan="3" class="text-right">TOTAL VALUE:</td>
            <td class="text-right">₦{{ number_format($totalValue, 2) }}</td>
            <td></td>
        </tr>
    </table>

    <!-- Financial Summary -->
    <div class="section-title">3. FINANCIAL TERMS AND CONDITIONS</div>
    <table>
        <colgroup>
            <col style="width: 40%;">
            <col style="width: 20%;">
            <col style="width: 20%;">
            <col style="width: 20%;">
        </colgroup>
        @if($application->insurance_amount)
        <tr>
            <td width="25%"><strong>Insurance ({{ $application->insurance_rate }}%):</strong></td>
            <td width="25%">₦{{ number_format($application->insurance_amount, 2) }}</td>
            <td width="25%"><strong>Total Loan:</strong></td>
            <td width="25%">₦{{ number_format($application->total_loan, 2) }}</td>
        </tr>
        @endif
        @if($application->equity)
        <tr>
            <td><strong>Equity Held ({{ $application->equity_rate ?? 0 }}%):</strong></td>
            <td>₦{{ number_format($application->equity, 2) }}</td>
            <td><strong>You Receive:</strong></td>
            <td>₦{{ number_format($application->disbursed_amount, 2) }}</td>
        </tr>
        @endif
        @if($application->repayment_amount)
        <tr>
            <td colspan="3" class="text-right"><strong>Total Repayment Amount:</strong></td>
            <td class="text-right">₦{{ number_format($application->repayment_amount, 2) }}</td>
        </tr>
        @endif
    </table>

    <!-- Signatures -->
    <div style="margin-top: 40px; display: flex; justify-content: space-between;">
        <div style="width: 45%;">
            <div style="border-top: 1px solid #cbd5e0; width: 80%; margin: 30px 0 10px;"></div>
            <div style="font-weight: 600; font-size: 11px;">APPLICANT'S SIGNATURE</div>
            <div style="margin-top: 5px; font-size: 10px; color: #4a5568;">
                Name: {{ $application->farmer->full_name ?? 'N/A' }}<br>
                Date: _________________
            </div>
        </div>
        <div style="width: 45%;">
            <div style="border-top: 1px solid #cbd5e0; width: 80%; margin: 30px 0 10px; margin-left: auto;"></div>
            <div style="font-weight: 600; text-align: right; font-size: 11px;">FOR: {{ strtoupper($tenantDisplayName) }} STATE CHAPTER</div>
            <div style="margin-top: 5px; font-size: 10px; text-align: right; color: #4a5568;">
                Authorized Signatory<br>
                Date: _________________
            </div>
        </div>
    </div>

    <!-- Verification Section -->
    <table>
        <tr>
            <th>DOCUMENT VERIFICATION</th>
        </tr>
        <tr>
            <td class="text-center">
                <div style="margin: 10px 0;">
                    <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(120)->generate(url('/verify/' . $application->reference_number))) }}" alt="Verification QR Code">
                </div>
                <div style="font-size: 10px; margin-top: 5px;">
                    Scan to verify this document<br>
                    <strong>Verification URL:</strong> {{ url('/verify/' . $application->reference_number) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div style="margin: 0 auto; max-width: 80%; border-top: 1px solid #e2e8f0; padding-top: 10px;">
            <div style="margin-bottom: 5px; font-weight: 500; color: #4a5568; font-size: 10px;">
                {{ strtoupper($tenantDisplayName) }} STATE AGRICULTURAL INPUT SUPPORT PROGRAM
            </div>
            <div style="font-size: 8px; color: #a0aec0; letter-spacing: 0.5px;">
                This is an official document. Any unauthorized duplication is prohibited.
                <div style="margin-top: 3px;">
                    Document ID: {{ $application->reference_number }} | Generated on: {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="no-print" style="margin: 4rem 0 2rem; text-align: center;">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; margin-bottom: 2rem;">
            <button onclick="window.print()" class="action-btn print-btn" style="background: #1a365d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center;">
                <i class="fas fa-print" style="margin-right: 8px;"></i>Print Acknowledgment
            </button>
            <a href="{{ route('applications.slip.pdf', $application->uuid) }}" class="action-btn download-btn">
                <i class="fas fa-download" style="margin-right: 8px;"></i>Download PDF
            </a>
            <a href="{{ route('applications.verify', $application->reference_number) }}" class="action-btn verify-btn">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Verify Document
            </a>
        </div>
    </div>
    
    <style>
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            line-height: 1.5;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: white;
            margin: 0.25rem;
        }

        .print-btn {
            background-color: #2563eb;
        }

        .download-btn {
            background-color: #059669;
        }

        .verify-btn {
            background-color: #7c3aed;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .print-btn:hover {
            background-color: #1d4ed8;
        }

        .download-btn:hover {
            background-color: #047857;
        }

        .verify-btn:hover {
            background-color: #6d28d9;
        }

        .action-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
        }

        @media (max-width: 640px) {
            .action-btn {
                width: 100%;
                margin: 0.5rem 0;
            }
        }

        @media print {
            .action-btn {
                display: none !important;
            }
        }
    </style>

    <!-- Signatures -->
    <table style="margin-top: 20px;">
        <tr>
            <td width="50%" class="text-center" style="border: none;">
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto 5px;"></div>
                <div>Authorized Signature</div>
            </td>
            <td width="50%" class="text-center" style="border: none;">
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto 5px;"></div>
                <div>Date: {{ now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>For verification, please visit our website or scan the QR code above.</p>
        <p style="margin-top: 10px;">
            <strong>Document ID:</strong> {{ $application->uuid }} | 
            <strong>Generated:</strong> {{ now()->format('M d, Y H:i A') }} | 
            <strong>Page:</strong> 1 of 1
        </p>
    </div>
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            window.print();
        };
        
        // Add copy functionality for reference number
        document.addEventListener('DOMContentLoaded', function() {
            const referenceElement = document.querySelector('.reference');
            if (referenceElement) {
                referenceElement.style.cursor = 'pointer';
                referenceElement.title = 'Click to copy reference number';
                
                referenceElement.addEventListener('click', function() {
                    const refNumber = '{{ $application->reference_number }}';
                    navigator.clipboard.writeText(refNumber).then(function() {
                        const originalText = referenceElement.textContent;
                        referenceElement.textContent = 'Copied!';
                        
                        setTimeout(() => {
                            referenceElement.textContent = originalText;
                        }, 2000);
                    });
                });
            }
            
            // Add smooth scroll for anchor links
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
