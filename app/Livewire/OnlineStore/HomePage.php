<?php

namespace App\Livewire\OnlineStore;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category; // FIX 1: You MUST import the Category model

class HomePage extends Component
{
    // app/Models/Category.php
public function products()
{
    return $this->hasMany(Product::class);
}
    public function render()
    {
        
        // FIX 2: Simplified the return and added actual cart count logic
        return view('livewire.online-shop.home', [
            'featuredProducts' => Product::latest()->take(8)->get(),
             // New: specifically fetch 8 products from the Cosmetics category
        'cosmeticProducts' => Product::whereHas('category', function($query) {
            $query->where('name', 'Cosmetics'); 
        })->latest()->take(8)->get(),
        
        'categories' => Category::withCount('products')->get(),
        'cartCount' => collect(session()->get('cart', []))->sum('quantity') 
        ])->layout('layouts.guest');
    }
    
}