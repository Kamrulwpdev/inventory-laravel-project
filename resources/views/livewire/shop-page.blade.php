<div class="py-5" style="background: #f4f7f6; min-height: 100vh;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Categories</h5>
                    <div class="d-flex flex-column gap-2">
                        <button wire:click="$set('selectedCategory', null)" 
                                class="btn text-start p-0 {{ is_null($selectedCategory) ? 'text-primary fw-bold' : 'text-muted' }}">
                            All Products
                        </button>
                        @foreach($categories as $category)
                            <button wire:click="$set('selectedCategory', {{ $category->id }})" 
                                    class="btn text-start p-0 {{ $selectedCategory == $category->id ? 'text-primary fw-bold' : 'text-muted' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">Sort By</h5>
                    <select wire:model.live="sort" class="form-select form-select-sm border-0 bg-light">
                        <option value="latest">Newest First</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Explore Products</h2>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           class="form-control w-50 rounded-pill border-0 shadow-sm px-4" 
                           placeholder="Search brand or product...">
                </div>
               
               <div class="row g-4">
                    {{-- On the Shop Page, the variable is usually $products (paginated) --}}
                    @foreach($products as $product)
                        <div class="col-6 col-md-4 col-lg-4">
                            {{-- This injects your trendy Shopilax-style card --}}
                            @include('livewire.online-shop.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
                <div class="mt-5">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>