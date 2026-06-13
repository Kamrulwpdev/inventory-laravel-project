<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.4; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { margin-bottom: 20px; border-bottom: 1px dashed #000; padding-bottom: 10px; }
        .customer-info { margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        .items-table th { border-bottom: 1px solid #000; text-align: left; }
        .totals-table { margin-top: 15px; border-top: 1px dashed #000; padding-top: 5px; }
        .footer { margin-top: 20px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header text-center">
        <h2 style="margin:0;">QUICKSPACE POS</h2>
        <p style="margin:5px 0;">Order #{{ $printOrder->order_number }}<br>
        {{ $printOrder->created_at->format('M d, Y h:i A') }}</p>
    </div>

    <div class="customer-info">
        <strong>Customer Details:</strong><br>
        Name: {{ $printOrder->customer_name }}<br>
        Phone: {{ $printOrder->customer_phone }}<br>
        Address: {{ $printOrder->customer_address }}
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($printOrder->items as $item)
            <tr>
                <td>{{ $item['name'] }} (x{{ $item['quantity'] }})</td>
                <td class="text-right">${{ number_format($item['price'], 2) }}</td>
                <td class="text-right">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        @php
            // Calculate subtotal by subtracting delivery from the stored total_amount
            // Note: If you add a 'delivery_charge' column to your DB, use that instead of hardcoding logic
            $delivery = ($printOrder->total_amount > 500) ? 150 : 50; 
            $subtotal = $printOrder->total_amount - $delivery;
        @endphp
        <tr>
            <td class="text-right">Subtotal:</td>
            <td class="text-right" style="width: 80px;">${{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right">Delivery Fee:</td>
            <td class="text-right">${{ number_format($delivery, 2) }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Grand Total:</strong></td>
            <td class="text-right"><strong>${{ number_format($printOrder->total_amount, 2) }}</strong></td>
        </tr>
    </table>

    <div class="footer text-center">
        <p>Thank you for shopping with QuickSpace!<br>
        Please keep this receipt for your records.</p>
    </div>
</body>
</html>