<div class="container py-5 text-center">
    <div class="card border-0 shadow-sm p-5 mx-auto" style="max-width: 600px;">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
        </div>
        
        <h2 class="fw-bold mb-3">Thank You, {{ $order->customer_name }}!</h2>
        <p class="text-muted mb-4">Your order <strong>#{{ $order->order_number }}</strong> has been placed successfully. We will contact you soon at {{ $order->customer_phone }}.</p>

        <div class="bg-light p-4 rounded-3 mb-4">
            <div class="d-flex justify-content-between mb-2">
                <span>Amount Paid:</span>
                <span class="fw-bold">${{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Status:</span>
                <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('admin.orders.download', $order->id) }}" class="btn btn-primary btn-lg rounded-pill fw-bold">
                Download Receipt (PDF)
            </a>
            
            <a href="/shop" class="btn btn-outline-secondary rounded-pill">
                Back to Shop
            </a>
            
        </div>
    </div>
</div>