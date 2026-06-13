<?php

namespace App\Livewire\OnlineStore;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use Livewire\WithPagination;

class CategoryPage extends Component
{
    use WithPagination;

    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $category = Category::where('slug', $this->slug)->firstOrFail();
        
        $products = Product::where('category_id', $category->id)
            ->paginate(12);

        return view('livewire.online-shop.category-page', [
            'category' => $category,
            'products' => $products
        ])->layout('layouts.guest');
    }
}