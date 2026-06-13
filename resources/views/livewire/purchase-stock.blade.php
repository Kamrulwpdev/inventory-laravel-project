<div class="p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title m-0 fw-bold text-primary">
            <i class="bi bi-box-seam me-2"></i>Stock In / Purchase Entry
        </h2>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            @if (session()->has('message'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 10px;">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="storePurchase">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-600 text-muted small text-uppercase">Select Product</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <select wire:model="selected_product" class="form-select border-start-0 bg-light" style="height: 50px; border-radius: 0 10px 10px 0;">
                                <option value="">-- Search Product to Add Stock --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Available: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-600 text-muted small text-uppercase">Quantity Added</label>
                        <input type="number" wire:model="qty_bought" 
                               class="form-control bg-light" 
                               placeholder="e.g. 50"
                               style="height: 50px; border-radius: 10px;">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-600 text-muted small text-uppercase">Total Cost Price ($)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">$</span>
                            <input type="number" wire:model="cost" 
                                   class="form-control border-start-0 bg-light" 
                                   placeholder="0.00"
                                   style="height: 50px; border-radius: 0 10px 10px 0;">
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-purple shadow-sm px-5 py-2 fw-bold text-white rounded-pill" 
                            style="background: linear-gradient(to right, #6366f1, #9333ea); border: none;">
                        <i class="bi bi-plus-circle me-2"></i> Update Inventory
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4 p-3 border-start border-4 border-primary bg-white shadow-sm" style="border-radius: 0 10px 10px 0;">
        <p class="mb-0 small text-muted">
            <strong>Note:</strong> Saving this purchase will automatically increment the physical stock count in the 
            <a href="{{ route('products.index') }}" class="text-decoration-none fw-bold">Products List</a>.
        </p>
    </div>
</div>