<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; width: 300px; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { border-bottom: 1px dashed #000; text-align: left; }
        .total { font-weight: bold; font-size: 14px; margin-top: 10px; border-top: 1px solid #000; padding-top: 5px; }
    </style>
</head>
<body>
   <div style="width: 300px; font-family: sans-serif; font-size: 14px;">
    <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px;">
        <h2 style="margin: 0;">SUPER SHOP</h2>
        <p style="margin: 5px 0;">Payment Confirmation</p>
    </div>

    <div style="padding: 15px 0; border-bottom: 1px dashed #000;">
        <p><strong>Date:</strong> {{ $payment->created_at->format('d M Y, h:i A') }}</p>
        <p><strong>Customer:</strong> {{ $payment->customer->name }}</p>
        <p><strong>Phone:</strong> {{ $payment->customer->phone }}</p>
    </div>

    <div style="padding: 15px 0; text-align: center;">
        <span style="font-size: 18px;">Amount Paid:</span>
        <h1 style="margin: 5px 0;">${{ number_format($payment->amount, 2) }}</h1>
        <p>Payment Method: Cash</p>
    </div>

    <div style="border-top: 1px dashed #000; padding-top: 10px; text-align: center;">
        <p>Remaining Balance: <strong>${{ number_format($payment->customer->current_balance, 2) }}</strong></p>
        <p style="font-size: 12px;">Thank you for your payment!</p>
    </div>
</div>
</body>
</html>

