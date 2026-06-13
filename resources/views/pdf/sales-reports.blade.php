<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #333; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border: 1px solid #ddd; vertical-align: top; }
        .header { text-align: center; margin-bottom: 20px; }
        .total-box { text-align: right; margin-top: 20px; font-size: 14px; font-weight: bold; }
        .item-list { font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h2>QuickSpace POS - Sales Report</h2>
        <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="20%">Date/Time</th>
                <th width="15%">Customer</th>
                <th width="40%">Items Sold</th> <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>#{{ $sale->id }}</td>
                <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                <td>
                    <strong>{{ $sale->customer->name ?? 'Walk-in' }}</strong><br>
                    <small>{{ $sale->customer->phone ?? 'N/A' }}</small>
                </td>
                <td>
                    @foreach($sale->items as $item)
                        <div class="item-list">
                            • {{ $item->product->name }} (x{{ $item->quantity }})
                        </div>
                    @endforeach
                </td>
                <td style="font-weight: bold;">
                    ${{ number_format($sale->total_amount, 2) }}
                </td>
            </tr>
            @endforeach
            
            @else
    <tr>
        <td colspan="4">No sales records found.</td>
    </tr>
@endif
        </tbody>
    </table>

    <div class="total-box">
        Grand Total: ${{ number_format($sales->sum('total_amount'), 2) }}
    </div>
</body>
</html>