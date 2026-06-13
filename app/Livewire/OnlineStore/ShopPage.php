<?php

namespace App\Livewire\OnlineStore;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;

class ShopPage extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;
    public $sort = 'latest';
    protected $paginationTheme = 'bootstrap';

    // Reset page when searching or filtering
    public function updatingSearch() { $this->resetPage(); }
    public function updatedSelectedCategory() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $cart = session()->get('cart', []);

        if(isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated'); 
        $this->dispatch('notify', 'Added to cart!');
    }

    public function render()
    {
        // 1. Build the filtered query
        $query = Product::query()->where('stock', '>', 0);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // 2. Apply Sorting
        if ($this->sort == 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($this->sort == 'price_high') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        return view('livewire.shop-page', [
            // FIX: Use $query->paginate() so your filters actually work!
            'products' => $query->paginate(12),
            'categories' => Category::all()
        ])->layout('layouts.guest');
    }
}