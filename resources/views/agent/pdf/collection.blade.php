<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Collection Verification Report - {{ $application->reference_number ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #000;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header h2 {
            color: #333;
            margin: 10px 0 0 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #333;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #e0e0e0;
            padding: 8px 12px;
            font-weight: bold;
            color: #000;
            border-left: 4px solid #000;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 8px;
            font-weight: bold;
            color: #000;
            border-bottom: 1px solid #333;
        }
        .info-value {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #000;
        }
        .table th {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 10px;
            border: 1px solid #333;
        }
        .table tr:nth-child(even) {
            background-color: #f0f0f0;
        }
        .photo-box {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            margin: 15px auto;
            max-width: 400px;
        }
        .signature-box {
            border: 1px solid #000;
            padding: 15px;
            text-align: center;
            margin: 20px auto;
            width: 300px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #333;
            border-top: 1px solid #000;
            padding-top: 20px;
        }
        .location-info {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ccc;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ASSOCIATION OF FARMERS IN THE NORTHEAST OF NIGERIA</h1>
        <h2>COLLECTION VERIFICATION REPORT</h2>
        <p><strong>Season:</strong> {{ $season_name ?? 'N/A' }}</p>
        <p><strong>Generated:</strong> {{ $generated_at ?? now()->format('F d, Y \a\t H:i A') }}</p>
    </div>

    <!-- Farmer Information -->
    <div class="section">
        <div class="section-title">FARMER INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value">{{ $farmer->full_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Registration Number:</div>
                <div class="info-value">{{ $farmer->registration_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $farmer->phone ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">{{ $farmer->address ?? 'N/A' }}</div>
            </div>
            @if($farm)
            <div class="info-row">
                <div class="info-label">Farm Size:</div>
                <div class="info-value">{{ $farm->size ?? 'N/A' }} hectares</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Application Details -->
    <div class="section">
        <div class="section-title">APPLICATION DETAILS</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Reference Number:</div>
                <div class="info-value">{{ $application->reference_number ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tenant/Cooperative:</div>
                <div class="info-value">{{ $tenant_name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Loan:</div>
                <div class="info-value">₦{{ number_format($application->total_loan ?? 0, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Verifying Agent:</div>
                <div class="info-value">{{ optional($agent)->user->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Verification Date:</div>
                <div class="info-value">{{ optional($verification)->created_at ? $verification->created_at->format('F d, Y H:i A') : 'N/A' }}</div>
            </div>
        </div>

        @if(isset($verification->location_lat) && isset($verification->location_lng))
        <div class="location-info">
            <strong>Verification Location:</strong><br>
            Latitude: {{ $verification->location_lat }}<br>
            Longitude: {{ $verification->location_lng }}
        </div>
        @endif
    </div>

    <!-- Commodity Details -->
    <div class="section">
        <div class="section-title">COMMODITY COLLECTION DETAILS</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Commodity</th>
                    <th>Allocated</th>
                    <th>Collected</th>
                    <th>Variance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($commodity_allocations) && count($commodity_allocations) > 0)
                    @foreach($commodity_allocations as $allocation)
                        @php
                            $collected = $verification->collected_quantity ?? 0;
                            $variance = ($allocation->allocated_quantity ?? 0) - $collected;
                        @endphp
                        <tr>
                            <td>{{ $allocation->commodity_name ?? 'N/A' }}</td>
                            <td>{{ number_format($allocation->allocated_quantity ?? 0, 2) }}</td>
                            <td>{{ number_format($collected, 2) }}</td>
                            <td>{{ number_format($variance, 2) }}</td>
                            <td>
                                @if($collected >= ($allocation->allocated_quantity ?? 0))
                                    Complete
                                @elseif($collected > 0)
                                    Partial
                                @else
                                    Not Collected
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center;">No commodity data available</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if(isset($verification->collection_notes) && $verification->collection_notes)
            <div style="margin-top: 15px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ccc;">
                <strong>Collection Notes:</strong><br>
                {{ $verification->collection_notes }}
            </div>
        @endif
    </div>

    <!-- Photo Section -->
    @if(isset($verification->commodity_photo))
        <div class="section">
            <div class="section-title">VERIFICATION PHOTO</div>
            <div class="photo-box">
                @php
                    $photoPath = public_path('storage/' . $verification->commodity_photo);
                    $photoExists = file_exists($photoPath);
                @endphp
                
                @if($photoExists)
                    <img src="{{ $photoPath }}" style="max-width: 100%; max-height: 300px;" alt="Verification Photo">
                    <p style="margin-top: 10px; font-size: 10px; color: #666;">
                        Captured on {{ optional($verification->created_at)->format('F d, Y H:i A') ?? 'N/A' }}
                    </p>
                @else
                    <p style="color: #999;">Photo not available</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Signature Section -->
    @if(isset($verification->signature))
        <div class="section">
            <div class="section-title">FARMER'S SIGNATURE</div>
            <div class="signature-box">
                @php
                    $signaturePath = public_path('storage/' . $verification->signature);
                    $signatureExists = file_exists($signaturePath);
                @endphp
                
                @if($signatureExists)
                    <img src="{{ $signaturePath }}" style="max-width: 250px; max-height: 80px;" alt="Signature">
                @else
                    <p style="color: #999;">Signature not available</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>This is an automatically generated document from AFNON Management System</strong></p>
        <p>Document ID: {{ $verification->id ?? 'N/A' }} | Application Ref: {{ $application->reference_number ?? 'N/A' }}</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i A') }}</p>
        <p>For verification purposes, please contact the system administrator.</p>
    </div>
</body>
</html>