<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductList extends Component
{
    
    use WithFileUploads;

    protected $listeners = ['productAdded' => '$refresh'];

    public $productId, $name, $barcode, $sku, $price, $stock, $category_id, $description, $tags, $alert_threshold;
    public $searchCategory = '';
    public $newCategoryName;
    public $image, $existingImage;
    public $editingCategoryId = null;
    public $editCategoryName;

    public function storeCategory()
    {
        $this->validate([
            'newCategoryName' => 'required|string|unique:categories,name|max:255',
        ]);

        Category::create(['name' => $this->newCategoryName]);

        $this->newCategoryName = '';
        session()->flash('message', 'Category added successfully!');
        $this->dispatch('close-category-add-modal');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->editingCategoryId = $id;
        $this->editCategoryName = $category->name;
        $this->dispatch('show-category-edit-modal');
    }

    public function updateCategory()
    {
        $this->validate([
            'editCategoryName' => 'required|string|max:255|unique:categories,name,' . $this->editingCategoryId,
        ]);

        $category = Category::find($this->editingCategoryId);
        $category->update(['name' => $this->editCategoryName]);

        $this->editingCategoryId = null;
        $this->editCategoryName = '';
        session()->flash('message', 'Category updated!');
        $this->dispatch('close-category-edit-modal');
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            session()->flash('message', 'Category deleted successfully.');
        }
    }

    public function editProduct($id)
{
    $product = Product::findOrFail($id);
    $this->productId       = $product->id;
    $this->name            = $product->name;
    $this->barcode         = $product->barcode;
    $this->sku             = $product->sku;
    $this->price           = $product->price;
    $this->stock           = $product->stock;
    $this->category_id     = $product->category_id;
    $this->alert_threshold = $product->alert_threshold;
    $this->description     = $product->description;
    $this->tags            = $product->tags;

    // 1. Store the database path string here
    $this->existingImage   = $product->image; 

    // 2. IMPORTANT: Reset the file upload variable to null
    // This prevents the 'temporaryUrl' error on strings
    $this->image           = null; 

    $this->dispatch('show-edit-modal');
}

    public function updateProduct()
{
    // 1. Validation
    $this->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|unique:products,sku,' . $this->productId,
        'barcode' => 'nullable|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|max:2048', 
    ]);

   $product = Product::findOrFail($this->productId);
    $imageName = $this->existingImage;
    
   // 2. Image Handling
if ($this->image) {
    $newFileName = time() . '-' . str_replace(' ', '_', $this->image->getClientOriginalName());
    
    // --- ADD THIS LINE: This physically saves the file to your server ---
    $this->image->storeAs('', $newFileName, 'products'); 

    // Delete the old image if it exists
    if ($this->existingImage) {
        $filenameOnly = str_replace('image/products/', '', $this->existingImage);
        
        // Use the 'products' disk to delete to stay consistent with your config
        if (\Storage::disk('products')->exists($filenameOnly)) {
            \Storage::disk('products')->delete($filenameOnly);
        }
    }

    // Save the FULL relative path to match your Entry logic
    $imageName = 'image/products/' . $newFileName; 
}

    // 3. Database Update
    $product->update([
        'name'            => $this->name,
        'sku'             => $this->sku,
        'image'           => $imageName, // Save only the filename
        'barcode'         => $this->barcode,
        'price'           => $this->price,
        'stock'           => $this->stock,
        'category_id'     => $this->category_id,
        'alert_threshold' => $this->alert_threshold,
        'description'     => $this->description,
        'tags'            => $this->tags,
        // Make sure slug is updated if name changes to avoid 404s
        'slug'            => \Illuminate\Support\Str::slug($this->name), 
    ]);

    $this->reset(['image']); 
    $this->existingImage = $imageName;

    session()->flash('message', 'Product updated successfully!');
    $this->dispatch('close-edit-modal');
}

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            DB::table('sale_items')->where('product_id', $id)->delete();

            if ($product->image && file_exists(base_path($product->image))) {
                unlink(base_path($product->image));
            }

            $product->delete();
            session()->flash('message', 'Product deleted successfully.');
        }
    }
    

   public $search = ''; // Add this property

    public function render()
    {
        $query = Product::with('category');
    
        // Apply Search Filter
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            });
        }
    
        // Apply Category Filter (keep your existing logic)
        if ($this->searchCategory && $this->searchCategory !== 'all') {
            $query->where('category_id', $this->searchCategory);
        }
    
        return view('livewire.product-list', [
            'products'   => $query->orderBy('id', 'desc')->get(),
            'categories' => Category::orderBy('name', 'asc')->get()
        ])->layout('admin.dashboard');
    }
    
    
}