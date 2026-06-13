<div class="container mt-4">
    <div wire:ignore.self class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="productModalLabel">
                        <i class="fas fa-cart-plus me-2"></i>Add New Product
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    @livewire('product-entry', [], key('add-product-entry-form'))
                </div>
            </div>
        </div>
    </div>
    
    <div wire:ignore.self class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form wire:submit.prevent="updateProduct">
                    <div class="modal-body p-4">
                         <div class="row mb-3 text-center">
                            <div class="col-12">
                                <label class="form-label d-block">Product Image Preview (300x300)</label>
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="rounded shadow border" style="width: 300px; height: 300px; object-fit: cover;">
                                @elseif ($existingImage)
                                    <img src="{{ asset($existingImage) }}" class="rounded shadow border" style="width: 300px; height: 300px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded mx-auto border d-flex align-items-center justify-content-center" style="width: 300px; height: 300px;">
                                        <i class="fas fa-image fa-4x text-muted"></i>
                                    </div>
                                @endif
                                <input type="file" wire:model="image" class="form-control mt-2 mx-auto" style="max-width: 300px;">
                                @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" wire:model="name" class="form-control">
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" wire:model="barcode" class="form-control">
                                @error('barcode') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" wire:model="price" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" wire:model="stock" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select wire:model="category_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Alert Threshold</label>
                                <input type="number" wire:model="alert_threshold" class="form-control" title="Alerts you when stock hits this number">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">SKU</label>
                                <input type="text" class="form-control" wire:model="sku">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tags</label>
                                <input type="text" class="form-control" wire:model="tags" placeholder="e.g., fashion, summer">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0">Description</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" id="btn-visual" class="btn btn-primary active" onclick="switchEditMode('visual')">Visual</button>
                                    <button type="button" id="btn-text" class="btn btn-outline-primary" onclick="switchEditMode('text')">Text</button>
                                </div>
                            </div>
                            <div wire:ignore>
                                <div id="edit-editor-wrapper">
                                    <div id="edit-editor" style="height: 250px; background: white;">{!! $description !!}</div>
                                </div>
                                <textarea id="edit-text-area" class="form-control d-none" style="height: 250px; font-family: monospace;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info px-4">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-tags me-2"></i>Create New Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="storeCategory">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Name</label>
                            <input type="text" wire:model="newCategoryName" class="form-control form-control-lg" placeholder="e.g. Electronics, Beverages...">
                            @error('newCategoryName') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-4">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4 overflow-hidden" style="border-radius: 15px;">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center border-bottom-0">
    <div class="mb-2 mb-md-0">
        <h5 class="m-0 font-weight-bold text-dark"><i class="fas fa-boxes me-2 text-primary"></i> Inventory Overview</h5>
        <small class="text-muted">Manage and monitor your product stock levels</small>
    </div>

    <div class="flex-grow-1 mx-md-4 mb-2 mb-md-0" style="max-width: 400px;">
        <div class="input-group input-group-sm shadow-sm rounded-pill overflow-hidden border">
            <span class="input-group-text bg-white border-0 ps-3">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   class="form-control border-0 py-2 ps-1" 
                   placeholder="Search by name, SKU, or barcode..."
                   style="outline: none; box-shadow: none;">
            @if($search)
                <button class="btn bg-white border-0 text-muted pe-3" wire:click="$set('search', '')">
                    <i class="fas fa-times-circle"></i>
                </button>
            @endif
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light btn-sm rounded-pill px-3 border" data-bs-toggle="modal" data-bs-target="#categoryManagerModal">
            <i class="fas fa-cog me-1"></i> Categories
        </button>
        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="fas fa-plus me-1"></i> Add Product
        </button>
    </div>
</div>

    <div class="card-body p-0">
        @if (session()->has('message'))
            <div class="mx-3 mt-3 alert alert-success border-0 shadow-sm alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-uppercase text-muted small" style="letter-spacing: 0.5px;">
                        <th class="ps-4 py-3">Product</th>
                        <th class="py-3 text-center">Category</th>
                        <th class="py-3 text-center">Barcode</th>
                        <th class="py-3 text-center">Price</th>
                        <th class="py-3 text-center">Stock</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr wire:key="product-{{ $product->id }}" class="product-row">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="image-container me-3 shadow-sm rounded border" style="width: 48px; height: 48px; overflow: hidden; background: #f8f9fa;">
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="{{ $product->name }}">
                                        @else
                                            <div class="d-flex h-100 w-100 align-items-center justify-content-center">
                                                <i class="fas fa-image text-muted opacity-50"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $product->name }}</div>
                                        <div class="text-muted small">ID: #{{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-soft-info text-info border border-info px-3 py-2">
                                    {{ $product->category->name ?? 'None' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <code class="text-muted badge rounded-pill bg-soft-info small fw-bold">{{ $product->barcode }}</code>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">${{ number_format($product->price, 2) }}</span>
                            </td>
                            <td class="text-center">
                                @if($product->stock <= $product->alert_threshold)
                                    <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-soft-danger text-danger">
                                        <i class="fas fa-exclamation-triangle me-1 small"></i>
                                        <span class="fw-bold">{{ $product->stock }}</span>
                                    </div>
                                @else
                                    <div class="d-inline-flex align-items-center px-2 py-1 rounded bg-soft-success text-success">
                                        <span class="fw-bold">{{ $product->stock }} </span>
                                    </div>
                                @endif
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="btn-group rounded shadow-sm">
                                    
                                    <a href="{{ route('product.details', $product->slug) }}" 
                                       target="_blank" 
                                       class="btn btn-white btn-sm px-3 border-end d-flex align-items-center" 
                                       title="View Full Details">
                                        <i class="fas fa-eye text-info"></i>
                                    </a>
                            
                                    <button wire:click="editProduct({{ $product->id }})" 
                                            class="btn btn-white btn-sm px-3 border-end" 
                                            title="Edit">
                                        <i class="fas fa-edit text-primary"></i>
                                    </button>
                            
                                    <button wire:click="deleteProduct({{ $product->id }})" 
                                            wire:confirm="Are you sure?" 
                                            class="btn btn-white btn-sm px-3" 
                                            title="Delete">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                                <p class="text-muted mb-0">No products match your inventory search.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Professional Soft Colors */
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .bg-soft-danger { background-color: rgba(220, 53, 69, 0.1); }
    
    .btn-white { background-color: #fff; border: 1px solid #e9ecef; }
    .btn-white:hover { background-color: #f8f9fa; }
    
    .product-row { transition: all 0.2s ease-in-out; }
    .product-row:hover { background-color: #fcfcfc !important; }
    
    .image-container { transition: transform 0.2s; }
    .product-row:hover .image-container { transform: scale(1.1); }

    /* Custom scrollbar for table-responsive */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
</style>
    
    
    <!--Manage Category modal-->
        <div wire:ignore.self class="modal fade" id="categoryManagerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header border-0 py-3" style="background: linear-gradient(45deg, #1a1a1a, #333333); color: white;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-layer-group me-2 text-info"></i>Manage Categories
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" style="background-color: #f8f9fa; max-height: 450px; overflow-y: auto;">
                <div class="list-group list-group-flush">
                    @forelse($categories as $cat)
                        <div class="list-group-item border-0 mb-1 mx-2 mt-2 rounded shadow-sm d-flex justify-content-between align-items-center transition-all hover-shadow" 
                             style="background: white; border-left: 4px solid #0dcaf0 !important;">
                            
                            <div class="flex-grow-1 me-3">
                                @if($editingCategoryId === $cat->id)
                                    <div class="input-group">
                                        <input type="text" 
                                               wire:model="editCategoryName" 
                                               class="form-control form-control-sm border-info" 
                                               autofocus>
                                        <button wire:click="updateCategory" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button wire:click="$set('editingCategoryId', null)" class="btn btn-sm btn-light border">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="fw-semibold text-dark">{{ $cat->name }}</span>
                                    <div class="text-muted small">ID: #{{ $cat->id }}</div>
                                @endif
                            </div>

                            @if($editingCategoryId !== $cat->id)
                                <div class="btn-group shadow-sm rounded">
                                    <button wire:click="editCategory({{ $cat->id }})" 
                                            class="btn btn-white btn-sm text-primary border-end" 
                                            title="Edit">
                                        <i class="fas fa-pen-nib"></i>
                                    </button>
                                    <button wire:click="deleteCategory({{ $cat->id }})" 
                                            wire:confirm="Deleting this will affect associated products. Continue?" 
                                            class="btn btn-white btn-sm text-danger" 
                                            title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-5 text-center">
                            <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                            <p class="text-muted">No categories found. Add one to get started!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="modal-footer border-0 bg-white py-3">
                <button type="button" class="btn btn-light fw-bold px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-dark fw-bold px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-2"></i>New Category
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow:hover { 
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .btn-white { background: #fff; border: 1px solid #eee; }
    .btn-white:hover { background: #f8f9fa; }
    /* Hide scrollbar for Chrome, Safari and Opera */
    .modal-body::-webkit-scrollbar { display: none; }
</style>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        let editQuill;
        let editCurrentMode = 'visual';

        function switchEditMode(mode) {
            const visualBtn = document.getElementById('btn-visual');
            const textBtn = document.getElementById('btn-text');
            const editorWrapper = document.getElementById('edit-editor-wrapper');
            const textArea = document.getElementById('edit-text-area');

            if (mode === 'text' && editCurrentMode === 'visual') {
                textArea.value = editQuill.root.innerHTML;
                editorWrapper.classList.add('d-none');
                textArea.classList.remove('d-none');
                visualBtn.classList.replace('btn-primary', 'btn-outline-primary');
                visualBtn.classList.remove('active');
                textBtn.classList.replace('btn-outline-primary', 'btn-primary');
                textBtn.classList.add('active');
                editCurrentMode = 'text';
            } else if (mode === 'visual' && editCurrentMode === 'text') {
                editQuill.root.innerHTML = textArea.value;
                textArea.classList.add('d-none');
                editorWrapper.classList.remove('d-none');
                textBtn.classList.replace('btn-primary', 'btn-outline-primary');
                textBtn.classList.remove('active');
                visualBtn.classList.replace('btn-outline-primary', 'btn-primary');
                visualBtn.classList.add('active');
                editCurrentMode = 'visual';
            }
        }

        document.addEventListener('livewire:initialized', function () {
            function initEditEditor() {
                const container = document.querySelector('#edit-editor');
                if (!container) return;

                editQuill = new Quill('#edit-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'header': [1, 2, 3, false] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['link', 'image', 'clean']
                        ]
                    }
                });

                editQuill.on('text-change', function() {
                    if(editCurrentMode === 'visual') {
                        @this.set('description', editQuill.root.innerHTML, false);
                    }
                });

                document.getElementById('edit-text-area').addEventListener('input', function(e) {
                    if(editCurrentMode === 'text') {
                        @this.set('description', e.target.value, false);
                    }
                });
            }

            initEditEditor();

            window.addEventListener('show-edit-modal', event => {
                setTimeout(() => {
                    if (!editQuill) initEditEditor();
                    if(editCurrentMode === 'text') switchEditMode('visual');
                    let currentDescription = @this.get('description');
                    editQuill.root.innerHTML = currentDescription || '';
                    var myModal = new bootstrap.Modal(document.getElementById('editProductModal'));
                    myModal.show();
                }, 150);
            });

            window.addEventListener('refresh-page-event', event => {
                setTimeout(function(){ window.location.reload(); }, 500); 
            });

            window.addEventListener('close-modal', event => {
                var modalEl = document.getElementById('productModal');
                var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();
            });

            window.addEventListener('close-edit-modal', event => {
                var modalEl = document.getElementById('editProductModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                if(modal) { modal.hide(); }
            });
            
            window.addEventListener('close-category-edit-modal', event => {
                var modalEl = document.getElementById('categoryManagerModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                if(modal) { modal.hide(); }
            });

            window.addEventListener('close-category-add-modal', event => {
                var modalEl = document.getElementById('addCategoryModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                if(modal) { modal.hide(); }
            });
        });
    </script>
</div>