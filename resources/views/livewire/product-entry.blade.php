<div class="p-3">
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
           <div class="col-md-12 mb-3">
            <label class="fw-bold">Product Image</label>
            <input type="file" wire:model="image" class="form-control" id="upload{{ $iteration ?? '' }}" onchange="previewImage(this)">
            
            <div class="mt-2">
                @if ($image && !is_string($image))
                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    
                @endif
        
                <img id="js-preview" class="img-thumbnail d-none" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
            
            @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        
            <div class="col-md-6 mb-3">
                <label class="fw-bold">Product Name</label>
                <input type="text" wire:model="name" class="form-control">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            
            <div class="col-md-6 mb-3">
                <label class="fw-bold">Barcode</label>
                <input type="text" wire:model="barcode" class="form-control">
                @error('barcode') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Category</label>
                <select wire:model="category_id" class="form-select">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Stock Alert Threshold</label>
                <input type="number" wire:model="alert_threshold" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="fw-bold">Price</label>
                <input type="number" wire:model="price" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label class="fw-bold">Stock</label>
                <input type="number" wire:model="stock" class="form-control">
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
                <label class="form-label fw-bold mb-0">Product Description</label>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" id="entry-btn-visual" class="btn btn-primary active" onclick="switchEntryMode('visual')">Visual</button>
                    <button type="button" id="entry-btn-text" class="btn btn-outline-primary" onclick="switchEntryMode('text')">Text</button>
                </div>
            </div>
            <div wire:ignore>
                <div id="entry-editor-wrapper">
                    <div id="editor" style="height: 250px; background: white;">{!! $description !!}</div>
                </div>
                <textarea id="entry-text-area" class="form-control d-none" style="height: 250px; font-family: monospace;"></textarea>
            </div>
        </div>
        
        <div class="text-end mt-3">
            <button type="submit" wire:loading.attr="disabled" class="btn btn-success px-5">
                <span wire:loading.remove><i class="fas fa-save me-1"></i> Save Product</span>
                <span wire:loading><i class="fas fa-spinner fa-spin me-1"></i> Processing...</span>
            </button>
        </div>
    </form>

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

  <script>
    // 1. Move global functions OUTSIDE the listener so HTML attributes can find them
    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('js-preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    }


    document.addEventListener('livewire:initialized', function () {
        
    let entryQuill;
    let entryMode = 'visual';

    function switchEntryMode(mode) {
        const visualBtn = document.getElementById('entry-btn-visual');
        const textBtn = document.getElementById('entry-btn-text');
        const wrapper = document.getElementById('entry-editor-wrapper');
        const textArea = document.getElementById('entry-text-area');

        if (mode === 'text' && entryMode === 'visual') {
            textArea.value = entryQuill.root.innerHTML;
            wrapper.classList.add('d-none');
            textArea.classList.remove('d-none');
            visualBtn.classList.replace('btn-primary', 'btn-outline-primary');
            visualBtn.classList.remove('active');
            textBtn.classList.replace('btn-outline-primary', 'btn-primary');
            textBtn.classList.add('active');
            entryMode = 'text';
        } else if (mode === 'visual' && entryMode === 'text') {
            entryQuill.root.innerHTML = textArea.value;
            textArea.classList.add('d-none');
            wrapper.classList.remove('d-none');
            textBtn.classList.replace('btn-primary', 'btn-outline-primary');
            textBtn.classList.remove('active');
            visualBtn.classList.replace('btn-outline-primary', 'btn-primary');
            visualBtn.classList.add('active');
            entryMode = 'visual';
        }
    }
    
        function initQuill() {
            const editorElement = document.querySelector('#editor');
            if (!editorElement) return;

            entryQuill = new Quill('#editor', {
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

            entryQuill.on('text-change', function() {
                let html = entryQuill.root.innerHTML;
                if(entryMode === 'visual') {
                    @this.set('description', html, false); 
                }
            });

            document.getElementById('entry-text-area').addEventListener('input', function(e) {
                if(entryMode === 'text') {
                    @this.set('description', e.target.value);
                }
            });
        }

        initQuill();

        // 2. Handle the success event (Product saved)
        Livewire.on('reset-editor', () => {
            // Reset Quill
            if (entryQuill) {
                entryQuill.setContents([]);
                entryQuill.root.innerHTML = '';
            }
            
            // Reset Text Area
            document.getElementById('entry-text-area').value = '';
            
            // Reset Image Preview (IMPORTANT)
            const jsPreview = document.getElementById('js-preview');
            jsPreview.src = '';
            jsPreview.classList.add('d-none');

            // Default back to visual mode
            if(entryMode === 'text') switchEntryMode('visual');

            // 3. Close the Modal (Assuming your modal ID is addProductModal)
            // Use standard JS if you don't use jQuery
            const modalElement = document.getElementById('addProductModal');
            if(modalElement) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if(modalInstance) modalInstance.hide();
            }
        });

        window.addEventListener('reinit-editor', () => {
            initQuill();
            });
            
            Livewire.on('close-product-modal', () => {
            // Replace 'addProductModal' with the actual ID of your modal div
            const modalElement = document.getElementById('addProductModal');
            if (modalElement) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
                modalInstance.hide();
            }
        });
    });
</script>
</div>