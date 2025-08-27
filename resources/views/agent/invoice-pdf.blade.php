<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $return->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice</h1>
        <p>Invoice Number: {{ $return->invoice_number }}</p>
        <p>Farmer: {{ $return->application->farmer->full_name }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($return->application->commodities as $commodity)
                @php
                    $unitPrice = $commodity->marketPrices()->where('season_id', $return->application->season_id)->latest()->first()?->current_price ?? $commodity->price_per_unit;
                    $qty = $commodity->quantity_per_hectare * $return->application->farm->size;
                    $total = $unitPrice * $qty;
                    $grandTotal += $total;
                @endphp
                <tr>
                    <td>{{ $commodity->name }}</td>
                    <td>{{ $qty }}</td>
                    <td>₦{{ number_format($unitPrice) }}</td>
                    <td>₦{{ number_format($total) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><strong>Insurance</strong></td>
                @php $insurance = $grandTotal * ($return->application->insurance_rate / 100); @endphp
                <td>₦{{ number_format($insurance) }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td>₦{{ number_format($grandTotal + $insurance) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
