<div x-data="{ showCart: @entangle('showCart') }"> {{-- Root element with Alpine.js sync --}}
        
        {{-- 1. Top Bar --}}
        <div class="qsTopBar bg-black text-white py-2" style="font-size: 13px;">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex gap-4">
                {{-- Email Link --}}
                <a href="mailto:support@shopilax.com" class="text-white text-decoration-none hover-pink transition">
                    <i class="bi bi-envelope me-2"></i>support@shopilax.com
                </a>
            
                {{-- Phone Link --}}
                <a href="tel:+8801830960840" class="text-white text-decoration-none hover-pink transition">
                    <i class="bi bi-headphones me-2"></i>+8801830960840
                </a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    {{-- Changed span to button to ensure Bootstrap registers the click --}}
                    <button class="btn dropdown-toggle border-0 p-0 text-white bg-transparent shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(app()->getLocale() == 'bn')
                            <img src="https://flagcdn.com/w20/bd.png" class="me-1"> Bangla
                        @else
                            <img src="https://flagcdn.com/w20/gb.png" class="me-1"> English
                        @endif
                    </button>
                    <ul class="dropdown-menu shadow border-0 mt-2">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', 'en') }}">
                                <img src="https://flagcdn.com/w20/gb.png" class="me-2" style="width: 20px;"> English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', 'bn') }}">
                                <img src="https://flagcdn.com/w20/bd.png" class="me-2" style="width: 20px;"> Bangla
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex gap-3 fs-6">
                    <a href="https://facebook.com/yourpage" target="_blank" class="text-white hover-pink transition">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://instagram.com/yourhandle" target="_blank" class="text-white hover-pink transition">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://youtube.com/yourchannel" target="_blank" class="text-white hover-pink transition">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="https://wa.me/8801830960840" target="_blank" class="text-white hover-pink transition">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
        {{-- 2. Main Header --}}
       <header class="py-3" style="background-color: #f3f4f7;">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none" wire:navigate>
                @php
                    // Replace this with your actual logic to check for a logo, 
                    // e.g., $settings->logo or a specific file path
                    $logoPath = 'path-to-your-logo.png'; 
                @endphp
            
                @if(file_exists(public_path($logoPath)) && $logoPath != '')
                    {{-- Show Image if it exists --}}
                    <img src="{{ asset($logoPath) }}" height="50" alt="QuickSpace">
                @else
                    {{-- Fallback to Text if image is missing --}}
                    <h2 class="fw-bold mb-0 text-dark">
                        Quick<span class="text-primary">Space</span>
                    </h2>
                @endif
            </a>

            {{-- SEARCH BAR --}}
            <div class="flex-grow-1 mx-5 position-relative" style="max-width: 700px;">
                <div class="input-group shadow-sm overflow-hidden rounded-pill">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="form-control border-0 py-2 px-4" 
                           placeholder="Search for products..."
                           style="outline: none; box-shadow: none;">
                    <button class="btn btn-black px-4 bg-black border-0">
                        <i class="bi bi-search text-white"></i>
                    </button>
                </div>
            
                {{-- Live Search Dropdown --}}
                @if(strlen($search) >= 2)
                    <div class="position-absolute w-100 mt-2 bg-white shadow-lg rounded-4 overflow-hidden" style="z-index: 9999;">
                        @if(count($searchResults) > 0)
                            @foreach($searchResults as $result)
                                <a href="{{ route('product.details', $result->slug) }}" 
                                   wire:navigate 
                                   class="d-flex align-items-center p-3 border-bottom text-decoration-none hover-bg-light transition">
                                    <img src="{{ asset($result->image) }}" width="40" height="40" class="rounded object-fit-cover me-3">
                                    <div>
                                        <div class="text-dark fw-bold mb-0" style="font-size: 0.9rem;">{{ $result->name }}</div>
                                        <div class="text-pink fw-bold small">৳{{ number_format($result->price) }}</div>
                                    </div>
                                </a>
                            @endforeach
                            
                            <a href="{{ route('search.results', ['query' => $search]) }}" 
                               wire:navigate
                               class="d-block text-center py-2 text-primary small fw-bold bg-light">
                                See All Results
                            </a>
                        @else
                            <div class="p-3 text-center text-muted small">No products found for "{{ $search }}"</div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex align-items-center gap-3">
                <a href="/login" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #000;">Login / Register</a>
                <button class="btn bg-black text-white rounded-circle d-flex align-items-center justify-content-center p-0 hover-pink transition" 
                        style="width: 42px; height: 42px;" 
                        title="Wishlist">
                    <i class="bi bi-heart fs-5"></i>
                </button>
                <button wire:click="$set('showCart', true)" class="btn position-relative p-0 border-0">
                    <i class="bi bi-cart3 fs-4"></i>
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-primary" style="font-size: 10px;">{{ $cartCount }}</span>
                    @endif
                </button>
            </div>
        </div>
    </header>
    
        {{-- 3. Navigation --}}
<nav class="bg-white border-bottom shadow-sm">
        <div class="container d-flex align-items-center">
            <div class="category-menu-wrapper position-relative me-4">
                <button class="btn btn-black text-white rounded-0 py-2 px-4 d-flex align-items-center gap-3 fw-bold" style="width: 250px;">
                    <i class="bi bi-list fs-4"></i> Browse Categories
                </button>
                {{-- Dropdown list code here --}}
            <ul class="category-dropdown-list shadow-lg border-0 list-unstyled bg-white m-0 p-0">
                @foreach($categories as $category)
                    <li class="border-bottom">
                        <a class="d-flex justify-content-between align-items-center px-4 py-2 text-dark text-decoration-none" 
                           {{-- Check if slug exists; if not, use a dummy hash to prevent the crash --}}
                           href="{{ $category->slug ? route('category.show', $category->slug) : '#' }}" 
                           wire:navigate>
                            {{ $category->name }}
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </a>
                    </li>
                @endforeach
                
            </ul>
            </div>
            <div class="nav-links d-flex gap-4 fw-semibold text-secondary">
                <a href="/" class="text-decoration-none text-dark">Home</a>
                <a href="/shop" class="text-decoration-none text-dark">Shop Page</a>
                <a href="/online-store" class="text-decoration-none text-dark">Category</a>
                <a href="/clearance" class="text-decoration-none text-dark">Clearance</a>
                <a href="/track" class="text-decoration-none text-dark">Track You order</a>
                <a href="#" class="text-decoration-none text-dark">News and Reviews </a>
                <a href="#" class="text-decoration-none text-dark">Help And Support</a>
            </div>
        </div>
    </nav>
    
    
        {{-- 4. Cart Sidebar (Slide-in effect) --}}
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

</div>

<style>
/*heder right scial icon*/
.transition {
    transition: all 0.3s ease;
}

.text-white {
    color: #ffffff !important;
    text-decoration: none;
}

.hover-pink:hover {
    color: #ff4d8d !important; /* This matches the Shopilax pink button */
    transform: translateY(-2px); /* Slight lift effect */
}

/*top header left mail number*/
.qsTopBar .d-flex.gap-4 a {
    font-size: 16px;
}
.qsTopBar .d-flex.gap-4 a i {
    font-size: 20px;
    color: #dc3545;
    background: #131d26;
    border-radius: 30px;
    width: 30px;
    height: 30px;
    display: inline-block;
    text-align: center;
}

/*langual swticher*/
.cursor-pointer {
    cursor: pointer;
}

/* Optional: remove the arrow icon if you don't want it */
.dropdown-toggle::after {
    display: inline-block;
    margin-left: 0.255em;
    vertical-align: 0.255em;
}

.btn-black {
    background-color: #000 !important;
    color: white !important;
}

.cursor-pointer {
    cursor: pointer;
}

.nav-links a:hover {
    color: #ff4d8d !important;
}

/* Make sure categories line up exactly with the button */
.category-dropdown-list {
    width: 250px !important;
    border-top: 3px solid #ff4d8d !important; /* The pink accent line */
}

    
    /* Ensure the wrapper maintains its area so the menu doesn't flicker */
.category-menu-wrapper {
    z-index: 1050;
}

/* Hide the menu by default and set its width to match the button */
.category-dropdown-list {
    position: absolute;
    top: 100%;
    left: 0;
    width: 250px; /* Match Shopilax sidebar width */
    display: none;
    background: #fff;
    border: 1px solid #eee !important;
}

/* THE HOVER MAGIC: Show menu when hovering over the wrapper */
.category-menu-wrapper:hover .category-dropdown-list {
    display: block;
}

/* Style the links to match your UI */
.category-dropdown-list li a {
    transition: all 0.2s ease;
    font-weight: 500;
}

.category-dropdown-list li a:hover {
    background-color: #f8f9fa;
    color: #0d6efd !important; /* Your primary blue color */
    padding-left: 30px !important; /* Slight shift effect */
}
</style>