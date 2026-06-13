<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ProductEntry extends Component
{
    use WithFileUploads;

    public $name, $barcode, $category_id, $alert_threshold = 10, $price, $stock, $sku, $tags, $description, $image;
    public $iteration = 1;

    protected $rules = [
        'name' => 'required|min:3',
        'category_id' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:5048',
        'description' => 'nullable|string',
    ];

public function save()
{
    try {
        $this->validate();

        $dbImagePath = null;

        if ($this->image) {
            // Generate a clean filename
            $imageName = time() . '-' . str_replace(' ', '_', $this->image->getClientOriginalName());
            
            // 1. Store the file using the NEW 'products' disk from your config.
            // Leave the first parameter empty ('') because the 'products' root 
            // already points to public_html/image/products.
            $this->image->storeAs('', $imageName, 'products'); 
            
            // 2. Save the folder structure to the DB so asset() can find it easily.
            // This prevents the "image on root" issue.
            $dbImagePath = 'image/products/' . $imageName; 
        }

        Product::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'barcode' => $this->barcode ?? '', 
            'category_id' => $this->category_id,
            'alert_threshold' => $this->alert_threshold ?? 10,
            'price' => $this->price,
            'stock' => $this->stock,
            'sku' => $this->sku ?? '',          
            'tags' => $this->tags ?? '',
            'description' => $this->description ?? '',
            'image' => $dbImagePath, 
        ]);

        // Reset and dispatch logic...
        $this->reset(['name', 'barcode', 'category_id', 'price', 'stock', 'sku', 'tags', 'description', 'image']);
        $this->iteration++; 
        
        $this->dispatch('close-product-modal'); 
        $this->dispatch('refresh-product-list');
        $this->dispatch('reset-editor');

        session()->flash('message', 'Product saved successfully!');
        
    } catch (\Exception $e) {
        // This will now catch "Could not move file" errors specifically.
        session()->flash('error', 'Upload Error: ' . $e->getMessage());
    }
}

    public function render()
    {
        return view('livewire.product-entry', [
            'categories' => Category::all()
        ]);
    }
}