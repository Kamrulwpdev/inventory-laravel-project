<div class="report-container" style="background: #f2edf3; padding: 25px; min-height: 100vh;">
    <style>
        .total-card {
            background: linear-gradient(to right, #278f86, #07cdae);
            color: white; border-radius: 15px; padding: 25px;
            box-shadow: 0 4px 20px rgba(7, 205, 174, 0.2);
            margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;
        }
        .filter-pill {
            padding: 8px 20px; border-radius: 20px; border: 1px solid #ebedf2;
            background: #fff; cursor: pointer; transition: 0.3s; font-size: 13px;
        }
        .filter-pill.active { background: #3e4b5b; color: white; border-color: #3e4b5b; }
        .report-card { background: white; border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .report-table thead th { background: #f8f9fa; border: none; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 15px; }
        .report-table td { padding: 15px; vertical-align: middle; border-top: 1px solid #f2edf3; }
        .export-btn { border-radius: 20px; padding: 8px 18px; border: none; color: white; font-weight: 600; font-size: 13px; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0">Sales Reports</h2>
        <div class="d-flex gap-2">
            <button wire:click="exportPdf" class="export-btn shadow-sm" style="background: linear-gradient(to right, #392c70, #6a008a);">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </button>
            <button wire:click="exportExcel" class="export-btn shadow-sm" style="background: linear-gradient(to right, #11998e, #38ef7d);">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </button>
        </div>
    </div>

    <div class="total-card">
        <div>
            <p class="small text-uppercase fw-bold opacity-75 mb-1">Total Net Revenue</p>
            <h1 class="fw-bold m-0">${{ number_format($totalRevenue, 2) }}</h1>
        </div>
        <i class="bi bi-cash-stack fs-1 opacity-50"></i>
    </div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="btn-group shadow-sm rounded-pill bg-white p-1">
        <button wire:click="$set('filter', 'all')" class="btn rounded-pill px-4 {{ $filter == 'all' ? 'btn-dark' : 'btn-light' }}" style="margin-right: 5px;">All</button>
        <button wire:click="$set('filter', 'today')" class="btn rounded-pill px-4 {{ $filter == 'today' ? 'btn-dark' : 'btn-light' }}" style="margin-right: 5px;">Today</button>
        <button wire:click="$set('filter', 'weekly')" class="btn rounded-pill px-4 {{ $filter == 'weekly' ? 'btn-dark' : 'btn-light' }}" style="margin-right: 5px;">Weekly</button>
        <button wire:click="$set('filter', 'monthly')" class="btn rounded-pill px-4 {{ $filter == 'monthly' ? 'btn-dark' : 'btn-light' }}" style="margin-right: 5px;">Monthly</button>
    </div>

    <div class="d-flex gap-2 align-items-center">
        <div class="input-group shadow-sm rounded-pill bg-white px-3 py-1" style="width: 500px;">
    <span class="d-flex align-items-center me-2 text-muted">
        <i class="bi bi-person-search"></i> 
    </span>
    <input type="text" 
           wire:model.live="search" 
           class="form-control border-0 shadow-none bg-transparent" 
           placeholder="Search customer or phone...">
    @if($search)
        <button wire:click="$set('search', '')" class="btn btn-link text-muted p-0 ms-2">
            <i class="bi bi-x-circle-fill"></i>
        </button>
    @endif
</div>

        <div class="d-flex align-items-center shadow-sm rounded-pill bg-white px-4 py-2">
            <label class="small text-muted me-2 mb-0">Date:</label>
            <input type="date" 
                   wire:model.live="selectedDate" 
                   wire:change="$set('filter', 'custom')" 
                   class="form-control border-0 shadow-none p-0 bg-transparent" 
                   style="width: 130px;">
        </div>
    </div>
</div>

    <div class="report-card">
        <div class="table-responsive">
            <table class="table report-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Sale ID</th>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Items Sold</th>
                        <th class="text-end pe-4">Total Amount</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#{{ $sale->id }}</td>
                            <td>
                                <div class="text-dark">{{ $sale->created_at->format('M d, Y') }}</div>
                                <div class="small text-muted">{{ $sale->created_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                                <div class="small text-muted">{{ $sale->customer->phone ?? '' }}</div>
                            </td>
                            <td>
                                @foreach($sale->items as $item)
                                    <div class="small text-muted">• {{ $item->product->name }} (x{{ $item->quantity }})</div>
                                @endforeach
                            </td>
                            <td class="text-end pe-4">
                                <h6 class="fw-bold text-dark m-0">
                                    {{-- CHECK: Use 'total' if 'total_amount' is empty --}}
                                    ${{ number_format($sale->total ?? $sale->total_amount, 2) }}
                                </h6>
                            </td>
                            <td class="text-center">
                                <button wire:click="viewSale({{ $sale->id }})" class="btn btn-link text-primary p-0">
                                    <i class="bi bi-eye-fill fs-5"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No sales data found for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!--popup modal-->
   <div wire:ignore.self class="modal fade" id="saleModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-light rounded-top-4">
                <h5 class="fw-bold mb-0">Digital Receipt Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-white py-4" style="overflow-y: auto; max-height: 70vh;">
                @if($selectedSale)
                    <div style="width: 80mm; margin: auto; border: 1px solid #eee; padding: 15px; box-shadow: 0 0 10px rgba(0,0,0,0.05); font-family: 'Courier New', Courier, monospace; color: #000;">
                        <center>
                            <h3 style="margin-bottom: 5px;">SUPER SHOP</h3>
                            <p style="font-size: 12px;">123 Business Road, City<br>Phone: 555-0199</p>
                        </center>
                        
                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                        
                        <div style="font-size: 13px;">
                            <strong>Date:</strong> {{ $selectedSale->created_at->format('d M Y, h:i A') }}<br>
                            <strong>Customer:</strong> {{ $selectedSale->customer->name ?? 'Walk-in' }}<br>
                            <strong>Phone:</strong> {{ $selectedSale->customer->phone ?? 'N/A' }}
                        </div>

                        <table style="width: 100%; font-size: 13px; margin-top: 10px;">
                            <thead>
                                <tr style="border-bottom: 1px solid #000;">
                                    <th style="text-align: left;">Item</th>
                                    <th style="text-align: right;">Qty</th>
                                    <th style="text-align: right;">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedSale->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td style="text-align: right;">x{{ $item->quantity }}</td>
                                    <td style="text-align: right;">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                        
                        <div style="text-align: right; font-size: 13px;">
                            @php $rawSub = $selectedSale->items->sum(fn($i) => $i->price * $i->quantity); @endphp
                            <p style="margin: 2px 0;">Subtotal: ${{ number_format($rawSub, 2) }}</p>

                            @if($selectedSale->manual_discount_amount > 0)
                                <p style="margin: 2px 0;">Promo: -${{ number_format($selectedSale->manual_discount_amount, 2) }}</p>
                            @endif

                            @if($selectedSale->redemption_discount_amount > 0)
                                <p style="margin: 2px 0;">Loyalty: -${{ number_format($selectedSale->redemption_discount_amount, 2) }}</p>
                            @endif
                            <p style="font-size: 1.2em; margin-top: 5px;"><strong>TOTAL: ${{ number_format($selectedSale->total_amount, 2) }}</strong></p>
                        </div>

                        @if($selectedSale->customer)
                        <div style="border: 1px solid #000; padding: 5px; margin-top: 10px; text-align: center; font-size: 12px;">
                            <strong>LOYALTY STATUS</strong><br>
                            Earned: {{ floor($selectedSale->total_amount) }} | Balance: {{ $selectedSale->customer->points }}
                        </div>
                        @endif

                        <center style="margin-top: 15px; font-size: 11px;">
                            <p>Thank you for shopping!<br>Software by QuickSpace POS</p>
                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" style="width: 100px;">
                        </center>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-0">
                <button wire:click="downloadReceipt({{ $selectedSale->id ?? 0 }})" class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                    <i class="bi bi-file-pdf me-2"></i> DOWNLOAD PDF RECEIPT
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('open-sale-modal', () => {
           new bootstrap.Modal(document.getElementById('saleModal')).show();
       });
    });
</script>

<script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('open-sale-modal', (event) => {
           var myModal = new bootstrap.Modal(document.getElementById('saleModal'));
           myModal.show();
       });
    });
</script>

    <div class="mt-4">
        <a href="/pos" class="text-decoration-none text-primary fw-bold small">
            <i class="bi bi-arrow-left me-1"></i> Return to POS Screen
        </a>
    </div>
</div>