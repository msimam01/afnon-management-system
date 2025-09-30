<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monetary Return Invoice</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; }
        .header { text-align: center; border-bottom: 3px solid #059669; padding-bottom: 8px; margin-bottom: 15px; }
        .header img { height: 70px; margin-bottom: 5px; }
        .header h1 { margin: 0; color: #065f46; }
        .details { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .card { width: 48%; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; }
        .card p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #d1d5db; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        tfoot td { font-weight: bold; background: #f9fafb; }
        .footer { text-align: center; font-size: 10px; margin-top: 25px; color: #6b7280; }
        .watermark { position: fixed; top: 35%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 80px; color: rgba(0, 0, 0, 0.05); text-align: center; z-index: -1; }
    </style>
</head>
<body>
    <div class="watermark">AFNEN</div>

    <div class="header">
        <img src="{{ public_path('images/afnon-logo.png') }}" alt="AFNEN Logo">
        <h1>Monetary Return Invoice</h1>
        <p>Invoice for Application: <strong>{{ $application->reference_number }}</strong></p>
    </div>

    <div class="details">
        <div class="card">
            <p><strong>Farmer:</strong> {{ $application->farmer->full_name }}</p>
            <p><strong>Reg. No.:</strong> {{ $application->farmer->registration_number }}</p>
            <p><strong>Phone:</strong> {{ $application->farmer->phone }}</p>
        </div>
        <div class="card">
            <p><strong>Invoice Date:</strong> {{ now()->format('d M, Y') }}</p>
            <p><strong>Season:</strong> {{ $application->season->name }}</p>
            <p><strong>Return Center:</strong> {{ $application->applicationCenter->returnCenter->name ?? 'N/A' }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                <th>Allocated Quantity</th>
                <th>Unit Price</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commoditiesBreakdown as $item)
                <tr>
                    <td>{{ $item['commodity_name'] }}</td>
                    <td>{{ number_format($item['allocated_quantity'], 2) }} kg</td>
                    <td>₦{{ number_format($item['unit_price'], 2) }}</td>
                    <td>₦{{ number_format($item['subtotal'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total Amount Due</strong></td>
                <td><strong>N{{ number_format($totalAmount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>This is a system-generated invoice. Please pay the total amount at the designated return center.</p>
        <p>Generated on {{ now()->format('d M, Y H:i') }} | AFNEN Loan System</p>
    </div>
</body>
</html>