<div class="container py-5">
    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/" wire:navigate class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Sidebar - 3 columns on desktop --}}
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar-wrapper sticky-top" style="top: 100px; z-index: 10;">
                {{-- Product Categories Widget --}}
                <div class="mb-5">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Product categories</h5>
                    <ul class="list-unstyled">
                        @foreach(\App\Models\Category::all() as $cat)
                            <li class="mb-2">
                                {{-- Use a conditional to check if the slug exists before calling the route --}}
                                <a href="{{ $cat->slug ? route('category.show', $cat->slug) : '#' }}" 
                                   wire:navigate 
                                   class="text-decoration-none {{ $category->id == $cat->id ? 'text-pink fw-bold' : 'text-muted' }} d-flex justify-content-between align-items-center">
                                    {{ $cat->name }}
                                    <i class="bi bi-chevron-right small"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Price Filter Widget --}}
                <div class="mb-5">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Filter by price</h5>
                    <div class="px-2">
                        <input type="range" class="form-range custom-range" min="0" max="10000" step="100">
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="small fw-bold">Price: ৳0 — ৳10,000</span>
                            <button class="btn btn-black btn-sm px-3 text-white rounded-0">FILTER</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content - 9 columns on desktop --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Category: “{{ $category->name }}”</h2>
                <div class="d-flex align-items-center gap-3">
                    <span class="small text-muted d-none d-md-block">Show : 9 / 12 / 18 / 24</span>
                    <select class="form-select form-select-sm border-0 bg-light rounded-pill px-3" style="width: 150px;">
                        <option>Relevance</option>
                        <option>Price: Low to High</option>
                    </select>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="row g-3">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
                            @include('livewire.online-shop.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-light rounded-4">
                    <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
                    <p class="text-muted fs-5">No products found in this category.</p>
                    <a href="/" wire:navigate class="btn btn-pink rounded-pill px-5">Continue Shopping</a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .text-pink { color: #ff4d8d !important; }
    .btn-pink { background-color: #ff4d8d; border: none; color: white; }
    .btn-pink:hover { background-color: #e63979; color: white; }
    .custom-range::-webkit-slider-thumb { background: #000; }
    .breadcrumb-item + .breadcrumb-item::before { content: "»"; }
</style>