<div class="container py-5">
    <h3 class="fw-bold mb-4">Search Results for: <span class="text-pink">"{{ $query }}"</span></h3>

    @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-3">
                    {{-- Reuse your product card design here --}}
                    @include('livewire.online-shop.product-card', ['product' => $product])
                    
                </div>
            @endforeach
        </div>
        <div class="mt-5">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-search text-muted display-1"></i>
            <p class="mt-3 text-muted">We couldn't find any products matching your search.</p>
            <a href="/shop" wire:navigate class="btn btn-primary rounded-pill px-4">Continue Shopping</a>
        </div>
    @endif
</div>

