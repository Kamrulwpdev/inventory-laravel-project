<div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card-hover bg-white p-3">
    <div class="position-relative mb-3">
        {{-- "NEW" Badge --}}
        @if($product->created_at >= now()->subDays(7))
            <span class="badge position-absolute top-0 start-0 m-2 px-3 py-2 rounded-pill bg-cyan text-white fw-bold" style="z-index: 1;">NEW</span>
        @endif

        {{-- Product Image --}}
        <a href="{{ route('product.details', $product->slug) }}" wire:navigate>
            <img src="{{ asset($product->image) }}" 
                 class="img-fluid rounded-3" 
                 alt="{{ $product->name }}" 
                 style="height: 220px; width: 100%; object-fit: cover;">
        </a>
    </div>

    <div class="card-body p-0">
        {{-- Product Title --}}
        <a href="{{ route('product.details', $product->slug) }}" wire:navigate class="text-decoration-none">
            <h5 class="text-dark fw-bold mb-1" style="font-size: 1.1rem;">{{ $product->name }}</h5>
        </a>

        {{-- Categories --}}
        <p class="text-muted small mb-3">
            @if($product->category)
                <a href="{{ route('category.show', $product->category->slug) }}" 
                   wire:navigate 
                   class="text-decoration-none text-muted hover-pink">
                    {{ $product->category->name }}
                </a>
            @else
                General
            @endif
        </p>

        {{-- Price Box --}}
        <div class="price-box border border-dark d-inline-block px-2 py-1 mb-3">
            <span class="fw-bold text-dark fs-5">{{ number_format($product->price, 2) }}৳</span>
        </div>

        {{-- Add to Cart Button --}}
        <button wire:click="$dispatch('addToCart', { productId: {{ $product->id }} })" 
                class="btn btn-black w-100 py-2 fw-bold text-white rounded-0 mb-3 text-uppercase">
            Add To Cart
        </button>

        {{-- SKU Display --}}
        <div class="sku-footer">
            <span class="text-muted fw-bold small">SKU:</span>
            <span class="text-secondary small">{{ $product->sku ?? 'N/A' }}</span>
        </div>
    </div>
</div>

<style>
    .bg-cyan { background-color: #70d1d1; }
    .btn-black { background-color: #000; border: none; }
    .btn-black:hover { background-color: #333; }
    .product-card-hover { transition: transform 0.2s ease-in-out; }
    .product-card-hover:hover { transform: translateY(-5px); }
    .price-box { min-width: 80px; text-align: center; }
</style>