<div wire:key="payment-collect-main">
    <style>
        /* Modern Container */
        .payment-wrapper { background: #f2edf3; min-height: 88vh; padding: 25px; border-radius: 15px; }
        
        /* Floating Cards */
        .glass-card { 
            background: #ffffff; 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.05); 
            margin-bottom: 25px;
            overflow: hidden;
        }

        /* Gradient Headings */
        .page-header {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: #343a40;
            display: flex;
            align-items: center;
        }
        .page-header i { color: #b66dff; margin-right: 15px; }

        /* Search Results List */
       
        .search-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f2edf3;
            transition: 0.2s;
            cursor: pointer;
        }
        .search-item:hover { background: #f8f9fa; border-left: 4px solid #b66dff; }
        /* Ensure the search container is the reference point */
.search-container {
    position: relative;
    z-index: 1050; /* Higher than the table */
}

/* Dropdown overlay logic */
.search-results-overlay {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 0 0 15px 15px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    border: 1px solid #ebedf2;
    max-height: 300px;
    overflow-y: auto;
    z-index: 9999;
}

/* Modernizing the Bottom List */
.table-modern-card {
    background: #ffffff;
    border-radius: 15px;
    padding: 0;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}

.table-modern-card .table {
    margin-bottom: 0;
}

.table-modern-card tr:last-child td {
    border-bottom: none;
}

        /* Loyalty Badge */
        .loyalty-box {
            background: linear-gradient(to right, #da8cff, #9a55ff);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }

        /* Action Buttons */
        .btn-confirm {
            background: linear-gradient(to right, #84d9d2, #07cdae);
            border: none;
            color: white;
            padding: 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            transition: 0.3s;
        }
        .btn-confirm:hover { opacity: 0.9; transform: scale(1.01); box-shadow: 0 5px 15px rgba(7, 205, 174, 0.3); }

        /* Table Styling */
        .table-modern { border-collapse: separate; border-spacing: 0 10px; }
        .table-modern tr { background: white; border-radius: 10px; }
        .table-modern td { padding: 15px; border: none !important; vertical-align: middle; }
        .table-modern thead th { background: transparent; border: none; font-size: 11px; text-transform: uppercase; color: #8c8c8c; letter-spacing: 1px; }
        .table-modern tr:hover { transform: scale(1.005); box-shadow: 0 5px 10px rgba(0,0,0,0.03); }
    </style>

    <div class="payment-wrapper">
        <h1 class="page-header"><i class="bi bi-wallet2"></i> Collect Customer Payment</h1>

        @if (session()->has('message'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" style="background: #da8cff20; color: #9a55ff;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-7">
                <div class="glass-card p-4">
                    <label class="fw-bold small text-muted mb-2">SEARCH CUSTOMER</label>
                    <div class="position-relative">
                        <div class="input-group mb-1 shadow-sm rounded-3 overflow-hidden">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" wire:model.live="search" class="form-control border-start-0 py-3" placeholder="Enter name or phone number...">
                        </div>
                        
                        @if(count($customers) > 0)
                            <ul class="search-results border">
                                @foreach($customers as $c)
                                    <li wire:click="selectCustomer({{ $c->id }})" class="search-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold text-dark">{{ $c->name }}</span>
                                            <div class="small text-muted">{{ $c->phone }}</div>
                                        </div>
                                        <div class="text-danger fw-bold">Due: ${{ number_format($c->current_balance, 2) }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    @if($selected_customer)
                        <div class="mt-4 pt-4 border-top">
                            <h5 class="fw-bold mb-3 text-muted">PAYMENT DETAILS</h5>
                            <div class="d-flex justify-content-between align-items-end mb-4 bg-light p-3 rounded-3">
                                <div>
                                    <small class="text-muted text-uppercase d-block">Customer Name</small>
                                    <span class="fs-5 fw-bold text-primary">{{ $selected_customer->name }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted text-uppercase d-block">Current Due</small>
                                    <span class="fs-3 fw-bold text-danger">${{ number_format($selected_customer->current_balance, 2) }}</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="fw-bold small text-muted mb-2">AMOUNT TO PAY ($)</label>
                                <input type="number" wire:model="payment_amount" class="form-control form-control-lg border-dark py-3 fw-bold fs-4" style="border-width: 2px;">
                                @error('payment_amount') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                            </div>

                            <button wire:click="recordPayment" class="btn btn-confirm w-100 mb-2 shadow-sm">
                                <i class="bi bi-cash-stack me-2"></i> CONFIRM CASH PAYMENT
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                @if($selected_customer)
                    <div class="glass-card p-4">
                        <h6 class="fw-bold text-muted mb-3">LOYALTY STATUS</h6>
                        <div class="loyalty-box shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-white text-primary rounded-circle p-2 me-3" style="width:45px; height:45px; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-star-fill fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $selected_customer->points }} Total Points</h5>
                                    <small class="opacity-75">Customer Rewards Program</small>
                                </div>
                            </div>
                            
                            @if($selected_customer->points >= 100)
                                <button wire:click="redeemPoints" class="btn btn-light w-100 fw-bold py-2 mt-2 shadow-sm" style="color: #9a55ff;">
                                    🎁 REDEEM 100 PTS ($5 OFF)
                                </button>
                            @else
                                <div class="progress mt-3" style="height: 10px; background: rgba(255,255,255,0.2);">
                                    <div class="progress-bar bg-white" style="width: {{ $selected_customer->points }}%;"></div>
                                </div>
                                <small class="mt-2 d-block text-center fw-bold">{{ 100 - $selected_customer->points }} pts remaining until next reward</small>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="glass-card p-5 text-center bg-white opacity-75">
                        <i class="bi bi-person-bounding-box fs-1 text-muted mb-3"></i>
                        <p class="text-muted">Select a customer to view payment history and loyalty points.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Payment History</h4>
        <button wire:click="recalculateAllPoints" class="btn btn-outline-primary btn-sm rounded-pill">Sync All</button>
    </div>
    
    <div class="table-modern-card overflow-hidden border">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-muted">
                    <th class="ps-4">CUSTOMER</th>
                    <th>TOTAL PAID</th>
                    <th class="text-center">REWARD POINTS</th>
                    <th>DATE</th>
                    <th class="text-end pe-4">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historyRecords as $record)
                <tr>
                    <td class="ps-4 fw-bold text-dark">{{ $record->customer->name }}</td>
                    <td><span class="badge bg-soft-success text-success fw-bold">+${{ number_format($record->total_paid, 2) }}</span></td>
                    <td class="text-center">
                        <span class="badge rounded-pill bg-light text-primary border px-3">⭐ {{ $record->customer->points }}</span>
                    </td>
                    <td class="text-muted small">{{ $record->customer->updated_at->format('d M Y') }}</td>
                    <td class="text-end pe-4">
                        <button wire:click="printReceipt({{ $record->customer_id }})" class="btn btn-dark btn-sm rounded-pill px-3">
                            <i class="bi bi-printer me-1"></i> Statement
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    </div>
</div>