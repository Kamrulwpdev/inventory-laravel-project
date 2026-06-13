<?php

namespace App\Livewire\OnlineStore;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

class Header extends Component
{
    public $showCart = false;
    public $search = ''; // This holds the live search input

    protected $listeners = ['addToCart' => 'addItem'];

    public function addItem($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);
        $this->showCart = true; 
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
    }
    
    public function goToCheckout()
    {
        return redirect()->route('checkout'); 
    }

    // New method to clear search after clicking a result
    public function clearSearch()
    {
        $this->reset('search');
    }

    public function render()
    {
        $cart = session()->get('cart', []);
        
        // --- SEARCH LOGIC START ---
        $searchResults = [];
        if (strlen($this->search) >= 2) {
            $searchResults = Product::where('name', 'like', '%' . $this->search . '%')
                ->where('stock', '>', 0)
                ->take(6) // Limit results for the dropdown
                ->get();
        }
        // --- SEARCH LOGIC END ---

        return view('livewire.online-shop.header', [
            'categories' => Category::select('id', 'name', 'slug')->get(), // Added slug here
            'cartItems' => $cart,
            'cartCount' => collect($cart)->sum('quantity'),
            'cartTotal' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
            'searchResults' => $searchResults, // Pass results to the blade
        ]);
    }
}