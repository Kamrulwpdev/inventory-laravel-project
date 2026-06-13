<style>
    .receipt-container { width: 80mm; margin: auto; font-family: 'Courier', sans-serif; }
    .text-right { text-align: right; }
    .dashed-line { border-top: 1px dashed #000; margin: 10px 0; }
    table { width: 100%; }
    th { border-bottom: 1px solid #000; text-align: left; }
</style>

<div class="receipt-container">
    <center>
        <h2>SUPER SHOP</h2>
        <p>123 Business Road, City<br>Phone: 555-0199</p>
    </center>
    
    <div class="dashed-line"></div>
    
    <p>
        <strong>Date:</strong> {{ $sale->created_at->format('d M Y, h:i A') }}<br>
        <strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in' }}<br>
        <strong>Phone:</strong> {{ $sale->customer->phone ?? 'N/A' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">x{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed-line"></div>
    
    <div class="text-right">
        @php
            $rawSubtotal = $sale->items->sum(fn($item) => $item->price * $item->quantity);
        @endphp

        <p>Subtotal: ${{ number_format($rawSubtotal, 2) }}</p>

        @if($sale->manual_discount_amount > 0)
            <p>Promo Discount: -${{ number_format($sale->manual_discount_amount, 2) }}</p>
        @endif

        @if($sale->redemption_discount_amount > 0)
            <p>Loyalty Reward: -${{ number_format($sale->redemption_discount_amount, 2) }}</p>
        @endif

        <div class="dashed-line"></div>
        <p style="font-size: 1.2em;"><strong>TOTAL: ${{ number_format($sale->total_amount, 2) }}</strong></p>
    </div>

    @if($sale->customer)
    <div style="border: 1px solid #000; padding: 8px; margin-top: 10px; text-align: center; background-color: #f9f9f9;">
        <strong>LOYALTY STATUS</strong><br>
        Points Earned this Visit: {{ floor($sale->total_amount) }}<br>
        New Point Balance: {{ $sale->customer->points }}
    </div>
    @endif

    <center style="margin-top: 20px;">
        <p>Thank you for shopping with us!</p>
        <small>Software by QuickSpace POS</small>
    </center>

    <div style="text-align: center; margin-top: 15px;">
        <img src="data:image/svg+xml;base64,{{ $qrCode }}" style="width: 120px; height: 120px;">
        <p style="font-size: 10px; margin-top: 5px;">Scan to Verify: #{{ $sale->id }}</p>
    </div>
</div>