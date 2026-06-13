<div wire:key="pos-terminal-main">
    <style>
        /* POS Layout System */
        .pos-wrapper { background: #f2edf3; min-height: 88vh; padding: 20px; border-radius: 15px; }
        
        /* Modern Scrollbar */
        .product-grid::-webkit-scrollbar { width: 4px; }
        .product-grid::-webkit-scrollbar-thumb { background: #da8cff; border-radius: 10px; }

        /* Left Section: Cart & Checkout */
        .cart-card { 
            background: #ffffff; 
            border-radius: 20px; 
            border: none; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        /*QTY */
        /* Clean Qty Input for POS */
.qty-input-modern {
    max-width: 60px;
    border: 1px solid #edf2f7 !important;
    background-color: #f8fafc !important;
    font-weight: 700 !important;
    color: #4a5568 !important;
    text-align: center;
    border-radius: 6px !important;
    padding: 4px 0 !important;
    transition: all 0.2s ease;
}

.qty-input-modern:focus {
    background-color: #ffffff !important;
    border-color: #4e73df !important;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1) !important;
    outline: none;
}

/* Hide arrows (spinners) */
.qty-input-modern::-webkit-outer-spin-button,
.qty-input-modern::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}


        /* Custom Discount Field Styling */
.discount-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 12px;
    transition: all 0.3s ease;
}

.discount-card:focus-within {
    border-color: #4e73df;
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.1);
}

.custom-input-group {
    display: flex;
    align-items: center;
    background: #f8f9fc;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #d1d3e2;
}

.discount-select {
    border: none !important;
    background: transparent !important;
    font-weight: 700;
    color: #4e73df;
    padding: 8px 12px;
    cursor: pointer;
    width: 60px;
    outline: none !important;
}

.discount-divider {
    width: 1px;
    height: 24px;
    background: #d1d3e2;
}

.discount-input {
    border: none !important;
    background: transparent !important;
    padding: 8px 15px;
    font-weight: 600;
    text-align: right;
    width: 100%;
    outline: none !important;
    color: #2e59d9;
}

/* Chrome, Safari, Edge, Opera - Remove arrows */
.discount-input::-webkit-outer-spin-button,
.discount-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.discount-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 800;
    color: #b7b9cc;
    margin-bottom: 6px;
    display: flex;
    justify-content: space-between;
}

        /* Product Cards (Cite: image_5198e3.jpg) */
        .product-item {
            background: #ffffff;
            border-radius: 15px;
            border: 1px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-align: center;
            overflow: hidden;
        }

        .product-item:hover {
            transform: translateY(-5px);
            border-color: #b66dff;
            box-shadow: 0 10px 20px rgba(182, 109, 255, 0.1);
        }

        .product-thumb {
            height: 100px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        /* Category Pills */
        .cat-pill {
            border-radius: 25px;
            padding: 8px 18px;
            font-size: 13px;
            border: 1px solid #ebedf2;
            background: #fff;
            transition: 0.2s;
            white-space: nowrap;
        }
        .cat-pill.active {
            background: linear-gradient(to right, #da8cff, #9a55ff);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(182, 109, 255, 0.3);
        }

        /* Action Buttons */
        .btn-complete {
            background: linear-gradient(to right, #84d9d2, #07cdae);
            border: none;
            color: white;
            font-weight: 700;
            padding: 15px;
            border-radius: 12px;
            transition: 0.3s;
        }
        .btn-complete:hover { opacity: 0.9; transform: scale(1.02); }

        .quick-reg-box {
            background: #fff;
            border: 1px dashed #b66dff;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Input Overrides */
        .pos-input {
            border-radius: 10px;
            border: 1px solid #ebedf2;
            padding: 12px;
            background: #fdfbff;
        }
        .pos-input:focus { border-color: #b66dff; box-shadow: 0 0 0 0.2rem rgba(182, 109, 255, 0.1); }
        
        /* Loyalty Reward Banner */
.reward-banner {
    background: linear-gradient(to right, #fff9e6, #fff);
    border: 1px solid #ffeeba;
    border-radius: 12px;
    padding: 12px 15px;
    margin-bottom: 20px;
    animation: fadeIn 0.4s ease-out;
}

.reward-icon {
    background: #ffc107;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

.btn-redeem {
    background: linear-gradient(to right, #ffc107, #ff9800);
    color: white;
    border: none;
    font-weight: 700;
    font-size: 13px;
    border-radius: 8px;
    padding: 8px 15px;
    transition: 0.3s;
}

.btn-redeem:hover {
    transform: scale(1.05);
    color: white;
    box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
    </style>

    <div class="pos-wrapper">
        <div class="row g-4">
            <div class="col-lg-7 col-xl-7">
                <div class="cart-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Terminal Manager</h4>
                        <span class="badge bg-light text-dark p-2 border">Station #01</span>
                    </div>

                    <div class="row g-2 mb-3">
                    <div class="col-md-12">
                        <input type="text" wire:model.live="search" class="form-control pos-input" placeholder="🔍 Search customer name/phone...">
                    </div>
                    <div class="col-md-12">
                        <select wire:model.live="customer_id" class="form-select pos-input">
                            <option value="">Walk-in Customer (Cash)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }} (Balance: ${{ number_format($customer->current_balance, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                    @if($selectedCustomer)
                        <div class="reward-banner d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="reward-icon me-3">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $selectedCustomer->name }}</div>
                                    <div class="small text-muted">Points: <span class="text-primary fw-bold">{{ $selectedCustomer->points }} pts</span></div>
                                </div>
                            </div>
                    
                            @if($selectedCustomer->points >= 100 && $discount_amount == 0)
                                <button wire:click="redeemPoints" class="btn btn-redeem shadow-sm">
                                    Redeem $5.00
                                </button>
                            @elseif($discount_amount > 0)
                                <span class="badge rounded-pill bg-success px-3 py-2">Reward Applied</span>
                            @endif
                        </div>
                    @endif

                    <div class="quick-reg-box">
                        <h6 class="text-primary fw-bold small mb-2"><i class="bi bi-person-plus-fill me-1"></i> Quick Register</h6>
                        <div class="d-flex gap-2">
                            <input type="text" wire:model="new_customer_name" class="form-control form-control-sm pos-input" placeholder="Full Name">
                            <input type="text" wire:model="new_customer_phone" class="form-control form-control-sm pos-input" placeholder="Phone">
                            <button wire:click="registerCustomer" class="btn btn-primary btn-sm px-3 rounded-3" style="background: #b66dff; border:none;">ADD</button>
                        </div>
                    </div>

                    <div class="input-group mb-4 shadow-sm rounded-3">
                        <span class="input-group-text bg-dark border-dark"><i class="bi bi-upc-scan text-white"></i></span>
                        <input type="text" id="barcodeInput" wire:model.live="barcode" class="form-control form-control-lg border-dark" placeholder="Scan Barcode or Type SKU...">
                    </div>

                    <div class="table-responsive flex-grow-1">
                        <table class="table align-middle">
    <thead class="bg-light">
        <tr class="small text-uppercase text-muted">
            <th>Item</th>
            <th class="text-center">Current</th>
            <th class="text-center">Update Qty</th>
            <th>Subtotal</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($cart as $id => $item)
            <tr wire:key="cart-item-{{ $id }}">
                <td>
                    <div class="fw-bold">{{ $item['name'] }}</div>
                    <div class="small text-muted">${{ number_format($item['price'], 2) }}</div>
                </td>
                
                <td class="text-center">
                    <span class="badge bg-soft-primary text-primary px-3">x{{ $item['qty'] }}</span>
                </td>

                <td class="text-center align-middle">
                    <div class="d-inline-block">
                        <input type="number" 
                               wire:model.live.debounce.500ms="cart.{{ $id }}.qty" 
                               wire:change="updateQuantity({{ $id }})"
                               class="form-control qty-input-modern" 
                               min="1">
                    </div>
                </td>

                <td class="fw-bold text-dark">${{ number_format($item['price'] * $item['qty'], 2) }}</td>
                
                <td class="text-end">
                    <button wire:click="removeItem({{ $id }})" class="btn btn-link text-danger p-0">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">Cart is empty. Ready for scanning.</td>
            </tr>
        @endforelse
    </tbody>
</table>
                    </div>

                    <div class="mt-auto pt-4 border-top">
    @php
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['qty']);
        // The controller handles the logic, but we calculate display total here
        $totalDue = max(0, $subtotal - $discount_amount - $points_redemption_value);
    @endphp

    <div class="d-flex justify-content-between mb-2">
        <span class="text-muted fw-medium">Subtotal</span>
        <span class="fw-bold text-dark">${{ number_format($subtotal, 2) }}</span>
    </div>

    <div class="discount-card mb-3">
        <div class="discount-label">
            <span>Manual Discount</span>
            @if($discount_amount > 0)
                <span class="text-primary fw-bold">-${{ number_format($discount_amount, 2) }}</span>
            @endif
        </div>

        <div class="custom-input-group">
            <select wire:model.live="discount_type" class="discount-select">
                <option value="fixed">$</option>
                <option value="percentage">%</option>
            </select>

            <div class="discount-divider"></div>

            <input type="number" 
                   wire:model.live="manual_discount" 
                   class="discount-input" 
                   placeholder="0.00"
                   step="0.01">
        </div>
    </div>

    @if($points_redemption_value > 0)
    <div class="d-flex justify-content-between mb-3 p-2 bg-success-subtle rounded-3 animate__animated animate__fadeIn">
        <span class="text-success fw-bold"><i class="bi bi-star-fill me-1"></i> Reward Applied</span>
        <span class="fw-bold text-success">-${{ number_format($points_redemption_value, 2) }}</span>
    </div>
    @endif

    <hr class="border-dashed my-3 opacity-25">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Total</h4>
            <small class="text-muted">Final amount to pay</small>
        </div>
        <h1 class="fw-bolder text-primary mb-0" style="letter-spacing: -1px;">
            ${{ number_format($totalDue, 2) }}
        </h1>
    </div>

    <div class="d-flex gap-2">
        <button wire:click="checkout" 
                class="btn btn-complete flex-grow-1 shadow-sm"
                @if(empty($cart)) disabled @endif>
            COMPLETE SALE
        </button>
        <a href="/payments" class="btn btn-light border px-4 d-flex align-items-center">
            <i class="bi bi-wallet2"></i> <span style="margin-left: 5px;">Collect Payments</span>
        </a>
    </div>
</div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-5">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-header bg-white border-0 p-3 pb-0">
                        <input type="text" wire:model.live.debounce.300ms="search_product" class="form-control pos-input mb-3" placeholder="Search Menu...">
                        <div class="d-flex gap-2 overflow-auto pb-3 no-scrollbar">
                            <button wire:click="$set('selected_category', null)" class="cat-pill {{ is_null($selected_category) ? 'active' : '' }}">All</button>
                            @foreach($categories as $cat)
                                <button wire:click="$set('selected_category', {{ $cat->id }})" class="cat-pill {{ $selected_category == $cat->id ? 'active' : '' }}">{{ $cat->name }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-body p-3 product-grid overflow-auto" style="height: 60vh;">
                        <div class="row row-cols-3 g-3">
                            @foreach($products as $product)
                                <div class="col">
                                    <div wire:click="addToCart({{ $product->id }})" class="product-item p-3 shadow-sm h-100">
                                        <div class="product-thumb mb-2 rounded-3">
                                            @if($product->image)
                                                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 150px;">
                                            @else
                                                <i class="bi bi-box text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="small fw-bold text-dark text-truncate">{{ $product->name }}</div>
                                        <div class="text-primary fw-bold mt-1">${{ number_format($product->price, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="beep-sound" src="{{ asset('public/sounds/beep.mp3') }}" preload="auto"></audio>
    <audio id="remove-sound" src="{{ asset('public/sounds/remove.mp3') }}" preload="auto"></audio>
    <audio id="success-sound" src="{{ asset('public/sounds/success.mp3') }}" preload="auto"></audio>

    @script
    <script>
        $wire.on('livewire:initialized', () => {
            const input = document.getElementById('barcodeInput');
            if(input) input.focus();
            document.addEventListener('click', () => { if(input) input.focus(); });
        });

        $wire.on('item-added', () => document.getElementById('barcodeInput')?.focus());
        $wire.on('play-beep', () => document.getElementById('beep-sound')?.play());
        $wire.on('play-remove-sound', () => document.getElementById('remove-sound')?.play());
        $wire.on('play-success-sound', () => document.getElementById('success-sound')?.play());
    </script>
    @endscript
</div>