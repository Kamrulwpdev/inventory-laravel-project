<div>
    <div class="p-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title m-0 fw-bold text-primary">
                <i class="bi bi-clock-history me-2"></i>Customer Sales History
            </h2>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" wire:model.live="search" 
                           class="form-control border-start-0" 
                           placeholder="Search by Customer Name, Email, or Order ID..."
                           style="height: 45px;">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small text-uppercase">Order ID</th>
                            <th class="py-3 text-muted small text-uppercase">Customer</th>
                            <th class="py-3 text-muted small text-uppercase text-center">Date</th>
                            <th class="py-3 text-muted small text-uppercase text-center">Total</th>
                            <th class="py-3 text-muted small text-uppercase text-center">Status</th>
                            <th class="pe-4 py-3 text-muted small text-uppercase text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td class="ps-4 fw-bold">#{{ $sale->order_number ?? $sale->id }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $sale->customer_name ?? 'Guest' }}</span>
                                    
                                    <span class="text-muted small">
                                        <i class="bi bi-telephone me-1"></i>{{ $sale->customer_phone ?? 'No Number' }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $sale->created_at->format('M d, Y') }}<br>
                                {{ $sale->created_at->format('h:i A') }}
                            </td>
                            <td class="text-center fw-bold text-dark">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $sale->status == 'completed' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}" 
                                      style="padding: 8px 15px;">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <button wire:click="viewDetails({{ $sale->id }})" class="btn btn-sm btn-outline-info border-0 shadow-sm me-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            
                                <a href="{{ route('sales.pdf', $sale->id) }}" target="_blank" class="btn btn-sm btn-outline-primary border-0 shadow-sm me-1">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            
                                <button onclick="confirm('Delete this record?') || event.stopImmediatePropagation()" 
                                        wire:click="deleteSale({{ $sale->id }})" 
                                        class="btn btn-sm btn-outline-danger border-0 shadow-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No records found for "{{ $search }}"
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} results
            </div>
            <div>
                {{ $sales->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
        </div>
    </div>
    
    <!--order detuals popup modal-->
    
    <div wire:ignore.self class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Sale Receipt Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($selectedOrder)
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-primary mb-0">QuickSpace Store</h4>
                    <small class="text-muted">Order #{{ $selectedOrder->order_number }}</small>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Customer Name:</span>
                    <span class="fw-bold">{{ $selectedOrder->customer_name }}</span>
                </div>
                 <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Customer Number:</span>
                    <span class="fw-bold">{{ $selectedOrder->customer_phone }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span class="text-muted">Order Date:</span>
                    <span class="fw-bold">{{ $selectedOrder->created_at->format('M d, Y h:i A') }}</span>
                </div>

                <hr class="border-dashed">

                @if(is_array($selectedOrder->items))
                    @foreach($selectedOrder->items as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                    @endforeach
                @endif

                <hr class="border-dashed">

                <div class="d-flex justify-content-between">
                    <h5 class="fw-bold">Total Paid:</h5>
                    <h5 class="fw-bold text-success">${{ number_format($selectedOrder->total_amount, 2) }}</h5>
                </div>
                @endif
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                @if($selectedOrder)
                <a href="{{ route('sales.pdf', $selectedOrder->id) }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-printer me-2"></i> Print Receipt
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('show-order-modal', event => {
        var myModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
        myModal.show();
    });
</script>
</div>

