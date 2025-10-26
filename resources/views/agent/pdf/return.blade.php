<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $report_type }}</title>
    <style>
        @page {
            margin: 1in;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 18px;
            color: #374151;
            margin: 5px 0;
            font-weight: normal;
        }
        .header p {
            margin: 5px 0;
            color: #6b7280;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .section h3 {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            padding: 4px 8px 4px 0;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            padding: 4px 0;
            vertical-align: top;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        .table td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            font-size: 11px;
            vertical-align: top;
        }
        .photo-section {
            text-align: center;
            margin: 20px 0;
        }
        .photo-container {
            display: inline-block;
            border: 1px solid #d1d5db;
            padding: 10px;
            margin: 10px;
        }
        .photo-label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        .photo {
            max-width: 200px;
            max-height: 150px;
            width: auto;
            height: auto;
        }
        .return-details {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            padding: 12px;
            margin: 15px 0;
        }
        .return-details.success {
            background-color: #ecfdf5;
            border-color: #a7f3d0;
        }
        .return-details.warning {
            background-color: #fef3c7;
            border-color: #f59e0b;
        }
        .return-details h4 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .return-details p {
            margin: 4px 0;
            font-size: 11px;
        }
        .signature-section {
            text-align: center;
            margin: 30px 0;
        }
        .signature-box {
            border: 2px solid #2563eb;
            padding: 20px;
            background-color: #f8fafc;
            display: inline-block;
            min-width: 200px;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 10px;
            color: #2563eb;
            display: block;
        }
        .generation-info {
            font-size: 10px;
            color: #6b7280;
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>{{ $organization_name }}</h1>
        <h2>{{ $season_name }}</h2>
        <p><strong>{{ $report_type }}</strong></p>
        <p><strong>Generated on:</strong> {{ $generated_at }}</p>
    </div>

    <!-- Farmer Details -->
    <div class="section">
        <h3>Farmer Information</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value">{{ $farmer->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Registration Number:</div>
                <div class="info-value">{{ $farmer->registration_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone Number:</div>
                <div class="info-value">{{ $farmer->phone ?? 'Not provided' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">{{ $farmer->address ?? 'Not provided' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Farm Size:</div>
                <div class="info-value">{{ $farm ? $farm->size . ' hectares' : 'Not specified' }}</div>
            </div>
        </div>
    </div>

    <!-- Season & Application Details -->
    <div class="section">
        <h3>Season & Allocation Information</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Season:</div>
                <div class="info-value">{{ $application->season->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tenant/Cooperative:</div>
                <div class="info-value">{{ $tenant_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Application Reference:</div>
                <div class="info-value">{{ $application->reference_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Verifying Agent:</div>
                <div class="info-value">{{ $agent ? $agent->user->name : 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Loan Amount:</div>
                <div class="info-value">₦{{ number_format($application->total_loan, 2) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Verification Date:</div>
                <div class="info-value">{{ $verification->created_at->format('l, F j, Y \a\t g:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Commodity Allocation Summary -->
    <div class="section">
        <h3>Commodity Allocation Summary</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Commodity</th>
                    <th>Allocated Qty</th>
                    <th>Collected Qty</th>
                    <th>Unit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commodity_allocations as $allocation)
                    @php
                        $collected = $allocation->collected_quantity ?? 0;
                        $allocated = $allocation->allocated_quantity;
                        $status = $collected >= $allocated ? 'Collected' : 'Partial Collection';
                        $statusColor = $collected >= $allocated ? '#059669' : '#f59e0b';
                    @endphp
                    <tr>
                        <td>{{ $allocation->commodity_name }}</td>
                        <td>{{ number_format($allocated, 2) }}</td>
                        <td>{{ number_format($collected, 2) }}</td>
                        <td>{{ $allocation->unit ?? 'N/A' }}</td>
                        <td style="color: {{ $statusColor }};">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Return Details Summary -->
    <div class="section">
        <h3>Return Details Summary</h3>

        <div class="return-details {{ $verification->variance >= 0 ? 'success' : 'warning' }}">
            <h4>Expected vs Actual Return</h4>
            <p><strong>Expected Quantity:</strong> {{ number_format($verification->expected_quantity, 2) }} units</p>
            <p><strong>Returned Quantity:</strong> {{ number_format($verification->returned_quantity, 2) }} units</p>
            <p><strong>Variance:</strong>
                @if($verification->variance > 0)
                    <span style="color: #dc2626;">+{{ number_format($verification->variance, 2) }} units (Shortfall)</span>
                @elseif($verification->variance < 0)
                    <span style="color: #059669;">{{ number_format(abs($verification->variance), 2) }} units (Excess)</span>
                @else
                    <span style="color: #059669;">0 units (Balanced)</span>
                @endif
            </p>
            <p><strong>Return Type:</strong> {{ $verification->partial_return ? 'Partial Return' : 'Complete Return' }}</p>
        </div>

        @if($verification->shortfall_reason)
            <div style="margin-top: 15px;">
                <strong>Shortfall Reason:</strong><br>
                {{ $verification->shortfall_reason }}
            </div>
        @endif
    </div>

    <!-- Return Photo -->
    <div class="photo-section">
        <div class="photo-container">
            <div class="photo-label">Return Verification Photo</div>
            @if($verification->returned_commodity_photo && Storage::exists($verification->returned_commodity_photo))
                <img src="{{ Storage::url($verification->returned_commodity_photo) }}" alt="Return verification photo" class="photo">
                <div style="margin-top: 10px; font-size: 10px; color: #6b7280;">
                    Taken on {{ $verification->created_at->format('M j, Y g:i A') }}
                </div>
            @else
                <div style="font-style: italic; color: #6b7280;">Photo not available</div>
            @endif
        </div>
    </div>

    <!-- Coordinator Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-label">Farmer's Signature</div>
            @if($verification->signature && Storage::exists($verification->signature))
                <img src="{{ Storage::url($verification->signature) }}" alt="Farmer signature" style="max-width: 180px; max-height: 60px;">
            @else
                <div style="font-style: italic; color: #6b7280;">Signature not available</div>
            @endif
        </div>
    </div>

    <!-- QR Code (Optional) -->
    @if(isset($qr_data) && $qr_data)
        <div class="qr-code">
            <p style="margin-bottom: 10px;"><strong>Digital Record QR Code</strong></p>
            <!-- QR Code would go here if you have a QR code generator -->
            <div style="border: 1px solid #d1d5db; padding: 20px; background: #f9fafb; display: inline-block;">
                <span style="font-size: 32px;">📱</span><br>
                <span style="font-size: 10px; color: #6b7280;">Scan to view digital record</span>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="generation-info">
        <p>This report was automatically generated by {{ $system_name }} on {{ now()->toDateTimeString() }}.</p>
        <p>For verification purposes, please contact the system administrator.</p>
    </div>

    {{-- IMPORTANT: This PDF template contains NO JavaScript. All data is server-rendered for PDF compatibility --}}
    {{-- Do NOT add <script> tags or DOM manipulation. Use {{ $variable }} for dynamic data only --}}

</body>
</html>
