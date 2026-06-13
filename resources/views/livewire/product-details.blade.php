<div class="container py-5 mt-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="img-fluid w-100" 
                     alt="{{ $product->name }}"
                     style="min-height: 500px; object-fit: cover;">
            </div>
            <div class="d-flex gap-3 mt-3">
                <div class="rounded-3 border border-primary p-1" style="width: 80px; height: 80px;">
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-2">
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small text-uppercase fw-bold">
                    <li class="breadcrumb-item"><a href="/shop" class="text-decoration-none text-muted">Shop</a></li>
                    <li class="breadcrumb-item text-primary active">{{ $product->category->name ?? 'Uncategorized' }}</li>
                </ol>
            </nav>

            <h1 class="fw-bold text-dark display-5 mb-3">{{ $product->name }}</h1>
            
            <div class="d-flex align-items-center mb-4">
                <h2 class="text-primary fw-bold mb-0 me-3">${{ number_format($product->price, 2) }}</h2>
                @if($product->stock < 5)
                    <span class="badge bg-soft-danger text-danger rounded-pill px-3 py-2">Low Stock: {{ $product->stock }} left</span>
                @else
                    <span class="badge bg-soft-success text-success rounded-pill px-3 py-2">In Stock</span>
                @endif
            </div>

            <p class="text-muted fs-5 mb-5 leading-relaxed">
                {{ $product->description ?? 'No description available for this premium product.' }}
            </p>

            <div class="d-flex gap-3 mb-5">
                <div class="input-group shadow-sm rounded-pill overflow-hidden" style="width: 140px;">
                    <button class="btn btn-light border-0 px-3" onclick="decrement()">-</button>
                    <input type="number" id="qty" value="1" class="form-control border-0 text-center fw-bold shadow-none" readonly>
                    <button class="btn btn-light border-0 px-3" onclick="increment()">+</button>
                </div>
                <button class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm flex-grow-1 py-3">
                    ADD TO CART
                </button>
            </div>

            <div class="card border-0 bg-light rounded-4 p-4">
                <h5 class="fw-bold mb-3">Product Specifications</h5>
                <div class="row gy-2">
                    <div class="col-6 text-muted">Category:</div>
                    <div class="col-6 fw-bold text-dark">{{ $product->category->name ?? 'N/A' }}</div>
                    <hr class="my-1 opacity-10">
                    <div class="col-6 text-muted">Stock ID:</div>
                    <div class="col-6 fw-bold text-dark">#SKU-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-danger { background-color: #fee2e2; }
    .bg-soft-success { background-color: #ecfdf5; }
    .rounded-4 { border-radius: 1.5rem !important; }
    .btn-primary { background-color: #6366f1; border: none; } /* Matches your dashboard primary color */
    .btn-primary:hover { background-color: #4f46e5; }
</style>

<script>
    function increment() { document.getElementById('qty').value++; }
    function decrement() { if(document.getElementById('qty').value > 1) document.getElementById('qty').value--; }
</script>