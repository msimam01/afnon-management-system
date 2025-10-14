@php
    // Tenant information
    $tenantName = tenant()->id ?? 'Tenant';
    $tenantDisplayName = ucfirst($tenantName);
    
    // Calculate total value of commodities
    $totalValue = 0;
    foreach ($application->commodities as $commodity) {
        $totalValue += $commodity->pivot->quantity * $commodity->price_per_unit;
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement Slip - {{ $application->reference_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .reference {
            font-weight: bold;
            margin-top: 10px;
            font-size: 14px;
            padding: 5px 10px;
            border: 1px solid #000;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 12px;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .signature {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 10px;
            width: 100%;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .no-border {
            border: none !important;
        }
        @media print {
            body {
                padding: 0;
                font-size: 12px;
            }
            .no-print {
                display: none;
            }
            table {
                page-break-inside: avoid;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
    {!! ToastMagic::styles() !!}
</head>

<body>
    <div class="header">
        <h1>ACKNOWLEDGEMENT SLIP</h1>
        <p>AGRICULTURAL INPUT SUPPORT PROGRAM</p>
        <p>{{ strtoupper($tenantDisplayName) }} STATE CHAPTER</p>
        <div class="reference">REF: {{ $application->reference_number }}</div>
    </div>

    <!-- Application Information -->
    <table>
        <tr>
            <th colspan="4">APPLICANT INFORMATION</th>
        </tr>
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
    <table>
        <tr>
            <th colspan="5">COMMODITIES ALLOCATION</th>
        </tr>
        <tr>
            <th width="40%">Commodity</th>
            <th width="15%" class="text-center">Quantity</th>
            <th width="15%" class="text-right">Unit Price (₦)</th>
            <th width="30%" class="text-right">Total (₦)</th>
        </tr>
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
    <table>
        <tr>
            <th colspan="4">FINANCIAL TERMS AND CONDITIONS</th>
        </tr>
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

    <!-- Action Buttons -->
    <div style="margin: 2rem 0; text-align: center;">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
            <button onclick="window.print()" class="action-btn print-btn">
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
    {!! ToastMagic::scripts() !!}
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
