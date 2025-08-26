@php
    $tenantName = tenant()->id ?? 'Tenant';
    $tenantDisplayName = ucfirst($tenantName);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Application Verification - {{ $application->reference_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.6;
            color: #1f2937;
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

        .verification-badge {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
            backdrop-filter: blur(10px);
        }
        }

        /* Details */
        .details {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .card {
            width: 48%;
            background: #ffffff;
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
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
        }
        tfoot td {
            font-weight: bold;
            background: #f9fafb;
        }

        /* QR Code */
        .qr {
            text-align: center;
            margin-top: 15px;
        }
        .qr img {
            margin-bottom: 8px;
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

        /* Footer */
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 30px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark">
        AFNON
    </div>

    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/afnon-logo.png') }}" alt="AFNON Logo">
        <h1>Application Verification Certificate</h1>
        <p>This document certifies that the following application is valid and recorded in the {{ $tenantName }} AFNON Management System.</p>
    </div>

    <!-- Details -->
    <div class="details">
        <div class="card">
            <p><strong>Reference:</strong> {{ $application->reference_number }}</p>
            <p><strong>Farmer:</strong> {{ $application->farmer->full_name }}</p>
            <p><strong>Reg. No.:</strong> {{ $application->farmer->registration_number }}</p>
        </div>
        <div class="card">
            <p><strong>Season:</strong> {{ $application->season->name }}</p>
            <p><strong>Farm Size:</strong> {{ $application->farm->size }} ha</p>
            <p><strong>Date:</strong> {{ now()->format('d M, Y') }}</p>
        </div>
    </div>

    <!-- Enhanced Commodity Breakdown Section -->
    <div style="margin: 25px 0;">
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 12px 20px; margin: 0 0 15px 0; font-size: 16px; font-weight: bold; border-radius: 8px; text-align: center; letter-spacing: 0.5px;">
            📦 COMMODITY VERIFICATION BREAKDOWN
        </div>

        <!-- Check for commodity allocations first (approved applications) -->
        @if($application->commodity_allocations && $application->commodity_allocations->count() > 0)
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <thead>
                <tr style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;">
                    <th style="padding: 12px 8px; text-align: left; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">COMMODITY</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">QTY/HA</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">FARM SIZE</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">ALLOCATED</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">UNIT PRICE</th>
                    <th style="padding: 12px 8px; text-align: right; font-size: 11px; font-weight: bold;">TOTAL VALUE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->commodity_allocations as $index => $allocation)
                <tr style="border-bottom: 1px solid #e5e7eb; {{ $index % 2 == 0 ? 'background: #f9fafb;' : 'background: white;' }}">
                    <td style="padding: 10px 8px; font-weight: bold; color: #1f2937;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 20px; height: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: white; font-size: 10px; font-weight: bold;">
                                {{ substr($allocation->commodity_name, 0, 1) }}
                            </div>
                            {{ $allocation->commodity_name }}
                        </div>
                    </td>
                    <td style="padding: 10px 8px; text-align: center; color: #4b5563;">{{ $allocation->qty_per_hectare }}</td>
                    <td style="padding: 10px 8px; text-align: center; color: #4b5563;">{{ $application->farm->size }} ha</td>
                    <td style="padding: 10px 8px; text-align: center;">
                        <span style="background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: bold;">
                            {{ $allocation->allocated_quantity }}
                        </span>
                    </td>
                    <td style="padding: 10px 8px; text-align: center; font-family: monospace; color: #4b5563;">₦{{ number_format($allocation->unit_price, 2) }}</td>
                    <td style="padding: 10px 8px; text-align: right; font-weight: bold; color: #059669;">₦{{ number_format($allocation->total_value, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <!-- Fallback to application commodities (pending applications) -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <thead>
                <tr style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;">
                    <th style="padding: 12px 8px; text-align: left; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">COMMODITY</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">QTY/HA</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">FARM SIZE</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">REQUESTED</th>
                    <th style="padding: 12px 8px; text-align: center; font-size: 11px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">UNIT PRICE</th>
                    <th style="padding: 12px 8px; text-align: right; font-size: 11px; font-weight: bold;">TOTAL VALUE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($application->commodities as $index => $commodity)
                @php
                    $requestedQty = $commodity->pivot->quantity ?? 0;
                    $qtyPerHectare = $commodity->quantity_per_hectare ?? 0;
                    $unitPrice = $commodity->price_per_unit ?? 0;
                    $totalValue = $requestedQty * $unitPrice;
                @endphp
                <tr style="border-bottom: 1px solid #e5e7eb; {{ $index % 2 == 0 ? 'background: #f9fafb;' : 'background: white;' }}">
                    <td style="padding: 10px 8px; font-weight: bold; color: #1f2937;">
                        <div style="display: flex; align-items: center;">
                            <div style="width: 20px; height: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: white; font-size: 10px; font-weight: bold;">
                                {{ substr($commodity->name, 0, 1) }}
                            </div>
                            {{ $commodity->name }}
                        </div>
                    </td>
                    <td style="padding: 10px 8px; text-align: center; color: #4b5563;">{{ $qtyPerHectare }}</td>
                    <td style="padding: 10px 8px; text-align: center; color: #4b5563;">{{ $application->farm->size }} ha</td>
                    <td style="padding: 10px 8px; text-align: center;">
                        <span style="background: #dbeafe; color: #1e40af; padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: bold;">
                            {{ $requestedQty }}
                        </span>
                    </td>
                    <td style="padding: 10px 8px; text-align: center; font-family: monospace; color: #4b5563;">₦{{ number_format($unitPrice, 2) }}</td>
                    <td style="padding: 10px 8px; text-align: right; font-weight: bold; color: #059669;">₦{{ number_format($totalValue, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Loan Summary Table -->
        <div style="margin-top: 25px;">
            <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; padding: 12px 20px; margin: 0 0 15px 0; font-size: 16px; font-weight: bold; border-radius: 8px; text-align: center; letter-spacing: 0.5px;">
                💰 LOAN SUMMARY & BREAKDOWN
            </div>

            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <thead>
                    <tr style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;">
                        <th style="padding: 12px 15px; text-align: left; font-size: 12px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">DESCRIPTION</th>
                        <th style="padding: 12px 15px; text-align: center; font-size: 12px; font-weight: bold; border-right: 1px solid rgba(255,255,255,0.2);">RATE/PERCENTAGE</th>
                        <th style="padding: 12px 15px; text-align: right; font-size: 12px; font-weight: bold;">AMOUNT (₦)</th>
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

                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 15px; font-weight: bold; color: #1f2937;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background: #10b981; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: white; font-size: 10px; font-weight: bold;">C</div>
                                Commodity Value
                            </div>
                        </td>
                        <td style="padding: 12px 15px; text-align: center; color: #6b7280; font-style: italic;">Base Amount</td>
                        <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #059669;">{{ number_format($commodityTotal, 2) }}</td>
                    </tr>

                    @if($application->insurance_amount)
                    <tr style="background: white; border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 15px; font-weight: bold; color: #1f2937;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background: #f97316; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: white; font-size: 10px; font-weight: bold;">I</div>
                                Insurance Premium
                            </div>
                        </td>
                        <td style="padding: 12px 15px; text-align: center; color: #f97316; font-weight: bold;">
                            {{ $application->insurance_rate ?? 0 }}%
                        </td>
                        <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #f97316;">{{ number_format($application->insurance_amount, 2) }}</td>
                    </tr>
                    @endif

                    @if($application->equity)
                    <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 15px; font-weight: bold; color: #1f2937;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 20px; height: 20px; background: #a855f7; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: white; font-size: 10px; font-weight: bold;">E</div>
                                Equity Contribution (Held)
                            </div>
                        </td>
                        <td style="padding: 12px 15px; text-align: center; color: #6b7280; font-style: italic;">Farmer's Share</td>
                        <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #a855f7;">{{ number_format($application->equity, 2) }}</td>
                    </tr>
                    @endif

                    @if($application->total_loan)
                    <tr style="background: white; border-bottom: 2px solid #059669;">
                        <td style="padding: 15px; font-weight: bold; color: #1f2937; font-size: 13px;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 22px; height: 22px; background: #3b82f6; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: white; font-size: 11px; font-weight: bold;">T</div>
                                TOTAL LOAN AMOUNT
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #6b7280; font-style: italic;">Final Amount</td>
                        <td style="padding: 15px; text-align: right; font-weight: bold; color: #3b82f6; font-size: 16px;">{{ number_format($application->total_loan, 2) }}</td>
                    </tr>
                    @endif

                    @if($application->disbursed_amount)
                    <tr style="background: #dcfce7; border-bottom: 1px solid #10b981;">
                        <td style="padding: 15px; font-weight: bold; color: #1f2937; font-size: 13px;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 22px; height: 22px; background: #10b981; border-radius: 4px; display: flex; align-items: center; justify-content: center; margin-right: 8px; color: white; font-size: 11px; font-weight: bold;">D</div>
                                AMOUNT DISBURSED
                            </div>
                        </td>
                        <td style="padding: 15px; text-align: center; color: #6b7280; font-style: italic;">Actually Paid</td>
                        <td style="padding: 15px; text-align: right; font-weight: bold; color: #10b981; font-size: 16px;">{{ number_format($application->disbursed_amount, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- QR Code -->
    <div class="qr">
        <img src="data:image/svg+xml;base64, {!! base64_encode(
            QrCode::format('svg')->size(150)->generate(route('applications.verify', $application->reference_number))
        ) !!} ">
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
