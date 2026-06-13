<div class="report-container" style="background: #f2edf3; padding: 25px; min-height: 100vh;">
    <style>
        /* Card & Header Styles */
        .order-card { background: white; border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03); overflow: hidden; }
        .page-title { font-weight: 700; color: #343a40; margin-bottom: 25px; }
        
        /* Gradient Status Badges */
        .badge-pending { background: linear-gradient(to right, #ffbf96, #fe7096); color: white; border: none; }
        .badge-completed { background: linear-gradient(to right, #84d9d2, #07cdae); color: white; border: none; }
        
        /* Table Aesthetics */
        .order-table thead th { 
            background: #f8f9fa; border: none; font-size: 11px; 
            text-transform: uppercase; letter-spacing: 1px; padding: 15px; 
        }
        .order-table td { padding: 15px; vertical-align: middle; border-top: 1px solid #f2edf3; }
        
        /* Action Buttons */
        .btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; transition: 0.3s; }
        .btn-mark-complete { background: #07cdae20; color: #07cdae; }
        .btn-mark-complete:hover { background: #07cdae; color: white; }
        .btn-pdf { background: #b66dff20; color: #b66dff; }
        .btn-pdf:hover { background: #b66dff; color: white; }
        .btn-del { background: #fe709620; color: #fe7096; }
        .btn-del:hover { background: #fe7096; color: white; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title m-0">Online Orders</h2>
        @if (session()->has('message'))
            <div class="alert alert-success border-0 py-2 px-4 shadow-sm rounded-pill" style="background: #07cdae; color: white;">
                <i class="bi bi-check-circle me-2"></i> {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="order-card border">
        <div class="table-responsive">
            <table class="table order-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Order ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Purchased Items</th>
                        <th>Order Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <h6 class="fw-bold m-0 text-dark">${{ number_format($order->total_amount, 2) }}</h6>
                        </td>
                        <td>
                            <span class="badge rounded-pill px-3 py-2 {{ $order->status == 'pending' ? 'badge-pending' : 'badge-completed' }}">
                                {{ strtoupper($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @foreach($order->items as $item)
                                    <span class="small text-muted">• {{ $item['name'] }} <strong class="text-dark">x{{ $item['quantity'] }}</strong></span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="small text-dark">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="extra-small text-muted">{{ $order->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                @if($order->status == 'pending')
                                    <button wire:click="markAsCompleted({{ $order->id }})" class="btn-action btn-mark-complete" title="Complete Order">
                                        <i class="bi bi-check2-circle"></i>
                                    </button>
                                @endif
                                
                                <a href="{{ route('admin.orders.download', $order->id) }}" class="btn-action btn-pdf" title="Download PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>

                                <button wire:click="deleteOrder({{ $order->id }})" 
                                        wire:confirm="Permanent delete this order?"
                                        class="btn-action btn-del" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>