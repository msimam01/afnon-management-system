<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monetary Returns Report - {{ now()->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background-color: #F8FAFC;
            padding: 20px;
            border-radius: 8px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item h3 {
            margin: 0;
            color: #4F46E5;
            font-size: 18px;
        }
        .summary-item p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background-color: #4F46E5;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        .table td {
            padding: 6px 8px;
            border: 1px solid #E5E7EB;
            font-size: 10px;
        }
        .table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .amount {
            font-weight: bold;
            color: #059669;
        }
        .status-paid {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
        }
        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
        }
        .status-failed {
            background-color: #FEE2E2;
            color: #991B1B;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding-top: 20px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monetary Returns Report</h1>
        <p>Generated on: {{ now()->format('F d, Y \a\t H:i A') }}</p>
        <p>Total Records: {{ $returns->count() }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary">
        <div class="summary-item">
            <h3>N{{ number_format($returns->where('status', 'paid')->sum('amount'), 2) }}</h3>
            <p>Total Collected</p>
        </div>
        <div class="summary-item">
            <h3>{{ $returns->where('status', 'paid')->count() }}</h3>
            <p>Paid Returns</p>
        </div>
        <div class="summary-item">
            <h3>{{ $returns->where('status', 'pending')->count() }}</h3>
            <p>Pending Returns</p>
        </div>
        <div class="summary-item">
            <h3>N{{ number_format($returns->where('status', 'paid')->avg('amount') ?? 0, 2) }}</h3>
            <p>Average Payment</p>
        </div>
    </div>

    <!-- Returns Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Transaction Ref</th>
                <th>Farmer Name</th>
                <th>Registration No</th>
                <th>Season</th>
                <th>Commodities</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $return)
            <tr>
                <td>{{ $return->tx_ref }}</td>
                <td>{{ $return->application->farmer->full_name }}</td>
                <td>{{ $return->application->farmer->registration_number }}</td>
                <td>{{ $return->application->season->name }}</td>
                <td>
                    @foreach($return->application->commodity_allocations as $allocation)
                        {{ $allocation->commodity_name }} ({{ $allocation->allocated_quantity }})
                        @if(!$loop->last), @endif
                    @endforeach
                </td>
                <td class="amount">N{{ number_format($return->amount, 2) }}</td>
                <td>
                    <span class="status-{{ $return->status }}">{{ ucfirst($return->status) }}</span>
                </td>
                <td>{{ $return->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated automatically by the AFNON Management System</p>
        <p>For any inquiries, please contact the system administrator</p>
    </div>
</body>
</html>
