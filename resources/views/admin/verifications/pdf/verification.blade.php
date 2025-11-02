<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Verification Report - {{ $verification->id }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 2px 0 0;
            font-size: 9pt;
        }
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 8px;
            font-size: 10pt;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
            border: 1px solid #ddd;
        }
        .info-table .label {
            font-weight: bold;
            width: 30%;
            background-color: #f5f5f5;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
            page-break-inside: avoid;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .data-table td {
            vertical-align: top;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 30px 0 5px;
            display: inline-block;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            margin: 0 30px;
            font-size: 9pt;
        }
        .page-number:before {
            content: "Page " counter(page);
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-uppercase {
            text-transform: uppercase;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Commodity {{ $type === 'collection' ? 'Collection' : 'Return' }} Verification</h1>
        <p>Document: Generated on: {{ $currentDate }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Farmer Name:</td>
            <td>{{ $farmer->full_name }}</td>
            <td class="label">Registration:</td>
            <td>{{ $farmer->registration_number }}</td>
        </tr>
        <tr>
            <td class="label">Phone:</td>
            <td>{{ $farmer->phone ?? 'N/A' }}</td>
            <td class="label">Center:</td>
            <td>{{ $center->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Season:</td>
            <td>{{ $season->name }}</td>
            <td class="label">Loan Type:</td>
            <td class="text-uppercase">{{ $season->loan_type }}</td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">
            {{ $type === 'collection' ? 'Commodity Collection' : 'Commodity Return' }} Details
            <span style="float: right;">Status:
                <strong class="text-uppercase">{{ $status }}</strong>
            </span>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Commodity</th>
                    <th class="text-right">Allocated ({{ $commodities[0]['unit'] ?? 'KG' }})</th>
                    <th class="text-right">{{ $type === 'collection' ? 'Collected' : 'Returned' }} ({{ $commodities[0]['unit'] ?? 'KG' }})</th>
                    <th class="text-right">Variance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commodities as $index => $commodity)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $commodity['name'] }}</td>
                    <td class="text-right">{{ number_format($commodity['allocated'], 2) }}</td>
                    <td class="text-right">{{ number_format($commodity['actual'], 2) }}</td>
                    <td class="text-right">
                        @if($commodity['difference'] != 0)
                            {{ $commodity['difference'] > 0 ? '+' : '' }}{{ number_format($commodity['difference'], 2) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
                @if(count($commodities) > 1)
                <tr>
                    <td colspan="2" class="text-right"><strong>Total:</strong></td>
                    <td class="text-right"><strong>{{ number_format($commodities->sum('allocated'), 2) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($commodities->sum('actual'), 2) }}</strong></td>
                    <td class="text-right">
                        @php $totalDiff = $commodities->sum('difference') @endphp
                        @if($totalDiff != 0)
                            <strong>{{ $totalDiff > 0 ? '+' : '' }}{{ number_format($totalDiff, 2) }}</strong>
                        @else
                            <strong>-</strong>
                        @endif
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if(!empty($verificationNotes))
    <div class="section">
        <div class="section-title">Verification Notes</div>
        <p>{{ $verificationNotes }}</p>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div>Verified By</div>
            <div><strong>{{ $approvedBy }}</strong></div>
            <div>{{ $verificationDate }}</div>
        </div>

        <div class="signature-box" style="float: right;">
            <div style="margin: 10px 0;">
                <img src="{{ asset('storage/tenant' . tenant('id') . '/app/public/' . $verification->signature) }}"
                     alt="Farmer Signature"
                     style="max-width: 150px; max-height: 60px; border: 1px solid #ccc;">
            </div>
            <div class="signature-line"></div>
            <div>Farmer's Signature</div>
            @if($verification->signature)
            @endif
            <div><strong>{{ $farmer->full_name }}</strong></div>
            <div>{{ $currentDate }}</div>
        </div>
    </div>

    <div class="footer">
        <p>AFNEN Management System | Page <span class="page-number"></span> of <span class="total-pages"></span></p>
    </div>
</body>
</html>
