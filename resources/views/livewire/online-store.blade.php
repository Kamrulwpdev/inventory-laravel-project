<div>
    <style>
        :root { --primary-purple: #6366f1; --soft-bg: #f8fafc; }
        body { background-color: var(--soft-bg); font-family: 'Inter', sans-serif; }
        .nav-link { font-weight: 500; color: #475569; }
        .hero-section { background: white; padding: 60px 0; border-bottom: 1px solid #e2e8f0; }
        .product-card { 
            border: none; border-radius: 16px; transition: transform 0.2s; 
            background: white; overflow: hidden;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .category-badge { 
            cursor: pointer; padding: 8px 20px; border-radius: 50px; 
            background: white; border: 1px solid #e2e8f0; transition: 0.3s;
        }
        .category-badge.active { background: var(--primary-purple); color: white; border-color: var(--primary-purple); }
        .cart-badge { background: var(--primary-purple); color: white; font-size: 10px; }.hover-primary:hover {
    color: var(--primary-purple) !important;
    transition: 0.2s;
}
    </style>

    {{-- ============================================================
         HELPER: resolve correct public URL for a product image path.
         - New uploads  → stored as "image/products/xxx.jpg"  → asset('image/products/xxx.jpg')
         - Old uploads  → stored as "products/xxx.jpg"        → asset('storage/products/xxx.jpg')
         - Null / empty → returns empty string (shows nothing)
         ============================================================ --}}
    @php
        function productImageUrl(?string $path): string {
            if (!$path) return '';
            // New path format written by the fixed ProductEntry / ProductList
            if (\Illuminate\Support\Str::startsWith($path, 'image/')) {
                return asset($path);
            }
            // Old path format written by Laravel Storage::disk('public')
            return asset('storage/' . $path);
        }
    @endphp

    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-5 fw-bold mb-3">Our Online Shop</h1>
            <p class="text-muted mx-auto" style="max-width: 600px;">
                Browse our latest products and order online. Your items will be ready for pickup or delivery.
            </p>
            <div class="mt-4 d-flex justify-content-center">
                <input type="text" wire:model.live="search"
                       class="form-control w-50 rounded-pill shadow-sm border-0 px-4"
                       placeholder="Search products...">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="d-flex gap-2 overflow-auto pb-3">
            <div wire:click="$set('selectedCategory', null)"
                 class="category-badge {{ is_null($selectedCategory) ? 'active' : '' }}">
                All Products
            </div>
            @foreach($categories as $category)
                <div wire:click="$set('selectedCategory', {{ $category->id }})"
                     class="category-badge {{ $selectedCategory == $category->id ? 'active' : '' }}">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>

        <div class="row mt-4">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card product-card h-100 shadow-sm">
                    <div class="position-relative">
                        {{-- Wrap Image in a link --}}
                       <a href="{{ route('product.details', $product) }}" wire:navigate>
                            @if($product->image)
                                <img src="{{ productImageUrl($product->image) }}"
                                     alt="{{ $product->name }}"
                                     style="width: 100%; height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="width: 100%; height: 200px;">
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                </div>
                            @endif
                        </a>
            
                        @if($product->stock < 10)
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Low Stock</span>
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h6 class="text-muted small mb-1">{{ $product->category->name ?? 'Uncategorized' }}</h6>
                        
                        {{-- Wrap Title in a link --}}
<h5 class="fw-bold mb-2">
    <a href="{{ route('product.details', $product) }}" wire:navigate class="text-decoration-none text-dark hover-primary">
        {{ $product->name }}
    </a>
</h5>
            
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fs-5 fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                            <!--<button wire:click="addToCart({{ $product->id }})"-->
                            <!--        class="btn btn-primary p-2 d-flex align-items-center justify-content-center w-50">-->
                            <!--    Add To Cart <i class="bi bi-plus-lg text-white ms-1"></i>-->
                            <!--</button>-->
                            <button 
                                wire:click="$dispatch('addToCart', { productId: {{ $product->id }} })" 
                                class="btn btn-primary rounded-pill w-50">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No products found in this category.</p>
                </div>
            @endforelse
        </div>
    </div>


    <footer class="bg-white py-5 mt-5 border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted"><strong>QuickSpace POS & Store</strong> &copy; {{ date('Y') }}</p>
            <p class="small text-muted">Developed By Kamrul Hasan</p>
        </div>
    </footer>
</div>