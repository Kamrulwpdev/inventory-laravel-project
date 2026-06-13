<div> 
    {{-- Cart Sidebar --}}
    <div class="fixed-top h-100 shadow-lg bg-white {{ $showCart ? 'd-block' : 'd-none' }}"
         style="width: 350px; left: auto; right: 0; z-index: 1060; transition: 0.3s;">
        
        <div class="p-4 d-flex flex-column h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Your Cart</h4>
                <button wire:click="$set('showCart', false)" class="btn-close"></button>
            </div>

            {{-- Scrollable Cart Items --}}
            <div class="flex-grow-1 overflow-auto">
                @if(count($cartItems) > 0)
                    @foreach($cartItems as $id => $item)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <img src="{{ \Illuminate\Support\Str::startsWith($item['image'] ?? '', 'image/') ? asset($item['image']) : asset('storage/' . ($item['image'] ?? '')) }}"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-0 fw-bold small text-truncate" style="max-width: 150px;">{{ $item['name'] }}</h6>
                                <small class="text-muted">{{ $item['quantity'] }} x ${{ number_format($item['price'], 2) }}</small>
                            </div>
                            
                            <button wire:click="removeFromCart({{ $id }})" class="btn btn-sm text-danger border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <p class="text-muted">Your cart is empty</p>
                    </div>
                @endif
            </div>

            @if(count($cartItems) > 0)
                <div class="border-top pt-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total:</span>
                        <span class="fw-bold text-primary fs-5">${{ number_format($cartTotal, 2) }}</span>
                    </div>
                    <a href="{{ url('/checkout') }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                        Proceed to Checkout
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar Backdrop --}}
    @if($showCart)
        <div wire:click="$set('showCart', false)" class="modal-backdrop fade show" style="z-index: 1050;"></div>
    @endif

    {{-- Main Content --}}
    {{-- Main Content Updated for Requested Style --}}
<div class="container py-5" style="margin-top: 30px;">
    <div class="row g-5 bg-white p-4 shadow-sm rounded-4">
        <div class="col-md-6">
            <div class="border rounded-4 overflow-hidden p-2">
                <img src="{{ $productImage }}" 
                     class="img-fluid w-100" 
                     alt="{{ $product->name }}"
                     style="max-height: 600px; object-fit: contain;">
            </div>
        </div>

        <div class="col-md-6 ps-md-5">
           <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Home</a></li>
                
                <li class="breadcrumb-item"><a href="{{ url('/online-store') }}" class="text-decoration-none">Shop</a></li>
                
                <li class="breadcrumb-item active">{{ $product->category->name ?? 'Product' }}</li>
            </ol>
        </nav>

            <h1 class="fw-bold mb-2 text-dark" style="font-size: 2rem;">{{ $product->name }}</h1>
            
            <div class="d-flex align-items-center mb-4">
                <h2 class="text-primary fw-bold mb-0 me-3">
                    {{ number_format($product->price, 2) }} <span class="fs-4">৳</span>
                </h2>
            </div>

            <div class="product-meta border-top pt-3 mb-4">
                <div class="mb-2"><strong>SKU:</strong> <span class="text-muted">{{ $product->sku ?? 'N/A' }}</span></div>
                <div class="mb-2"><strong>Category:</strong> <span class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</span></div>
                @if($product->tags)
                <div class="mb-2">
                    <strong>Tags:</strong> 
                    @foreach(explode(',', $product->tags) as $tag)
                        <span class="badge bg-light text-dark border ms-1">{{ trim($tag) }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="d-flex gap-3 mb-4">
                <div class="d-flex align-items-center bg-light rounded-pill px-2 border" style="width: 130px;">
                    <button wire:click="decrement" class="btn btn-link text-dark fw-bold text-decoration-none">-</button>
                    <input type="number" class="form-control border-0 bg-transparent text-center fw-bold shadow-none p-0" wire:model.live="quantity">
                    <button wire:click="increment" class="btn btn-link text-dark fw-bold text-decoration-none">+</button>
                </div>
                
                @if($product->stock > 0)
                    <button wire:click="addToCart" class="btn btn-dark px-5 rounded-pill fw-bold">
                        ADD TO CART
                    </button>
                @else
                    <button class="btn btn-secondary px-5 rounded-pill fw-bold" disabled>OUT OF STOCK</button>
                @endif
            </div>

            
            <!--buttom feature payment, social icon-->
            <div class="product-info-features mt-4" style="max-width: 550px; font-family: 'Inter', sans-serif;">
            
            <div class="p-3 rounded-3 mb-3 d-flex align-items-center" style="background-color: #ecf2ff; border: 1px solid #d9e6ff;">
                <div class="me-3 text-primary">
                    <img src="https://quickspace.shopilax.com/image/imagesx/delivery.svg">
                </div>
                <div>
                    <h6 class="mb-1 fw-bold" style="font-size: 16px; color: #000;">Estimated delivery time:</h6>
                    <div style="font-size: 16px; color: #000; line-height: 1.5;">
                        Received within <span class="fw-bold">1-2 days</span> <strong>inside of Feni</strong>.<br>
                        <span class="fw-bold">3-5</span> working days <strong>across all of Bangladesh</strong>
                    </div>
                </div>
            </div>
        
            <div class="row g-0 border rounded-2 mb-4 overflow-hidden" style="border-color: #ebebeb !important;">
                <div class="col-6 border-end border-bottom p-3 text-center" style="border-color: #ebebeb !important;">
                    <i class="fas fa-user-shield text-primary mb-2" style="font-size: 2rem;"></i>
                    <div class="fw-bold" style="font-size: 1rem;">Trust signals</div>
                    <div class="text-muted" style="font-size: 1rem;">100% Secure and safety.</div>
                </div>
                <div class="col-6 border-bottom p-3 text-center" style="border-color: #ebebeb !important;">
                    <i class="fas fa-fingerprint text-primary mb-2" style="font-size: 2rem;"></i>
                    <div class="fw-bold" style="font-size: 1rem;">Authentic products</div>
                    <div class="text-muted" style="font-size: 1rem;">Original cosmetics from global brands.</div>
                </div>
                <div class="col-6 border-end p-3 text-center" style="border-color: #ebebeb !important;">
                    <i class="fas fa-truck text-primary mb-2" style="font-size: 2rem;"></i>
                    <div class="fw-bold" style="font-size: 1rem;">Fast Delivery</div>
                    <div class="text-muted" style="font-size: 1rem;">Quick shipping across all of Bangladesh.</div>
                </div>
                <div class="col-6 p-3 text-center">
                    <i class="fas fa-hand-holding-usd text-primary mb-2" style="font-size: 2rem;"></i>
                    <div class="fw-bold" style="font-size: 1rem;">Cash on Delivery</div>
                    <div class="text-muted" style="font-size: 1rem;">Pay only after receiving your order.</div>
                </div>
            </div>
        
            <div class="mb-4">
                <img src="https://shopilax.com/wp-content/uploads/2024/10/Payment-Method-e1765731443463.png" 
                     alt="Payment Methods" 
                     class="img-fluid" 
                     style="max-height: 40px; width: auto;">
            </div>
        
            <div class="d-flex align-items-center gap-2 mt-3">
                <span class="fw-bold small text-uppercase" style="letter-spacing: 0.5px; color: #777;">Share:</span>
                <div class="d-flex gap-2">
                    <a href="#" class="share-btn fb"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="share-btn x-twitter"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" class="share-btn pin"><i class="fab fa-pinterest"></i></a>
                    <a href="#" class="share-btn li"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="share-btn tg"><i class="fab fa-telegram-plane"></i></a>
                </div>
            </div>
        </div>
        
        <style>
            .share-btn {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white !important;
                text-decoration: none;
                font-size: 0.85rem;
                transition: all 0.2s ease;
            }
            .share-btn:hover {
                transform: scale(1.1);
                opacity: 0.9;
            }
            .fb { background-color: #3b5998; }
            .x-twitter { background-color: #000000; }
            .pin { background-color: #cb2027; }
            .li { background-color: #007bb5; }
            .tg { background-color: #0088cc; }
        
            .product-info-features .row .col-6:hover {
                background-color: #f9f9f9;
            }
        </style>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h4 class="fw-bold border-bottom pb-2 mb-4">Description</h4>
            <div class="styled-description lh-lg">
                {!! $product->description !!}
            </div>
        </div>
    </div>
</div>

    <style>
        .bg-soft-primary { background-color: #eef2ff !important; }
        .bg-soft-success { background-color: #ecfdf5 !important; }
        .bg-soft-danger { background-color: #fef2f2 !important; }
        .rounded-4 { border-radius: 1.25rem !important; }
        .btn-primary { background-color: #6366f1; border: none; }
        .btn-primary:hover { background-color: #4f46e5; }
        .btn-outline-primary { color: #6366f1; border-color: #6366f1; }
        .btn-outline-primary:hover { background-color: #6366f1; border-color: #6366f1; color: white; }
    </style>
</div>