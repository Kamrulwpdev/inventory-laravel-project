<?php

namespace App\Livewire\OnlineStore;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;

class OnlineStore extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;

    // We only keep the properties needed for product display
    public function render()
    {
        $products = Product::query()
            ->where('stock', '>', 0)
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->when($this->selectedCategory, fn($q) => $q->where('category_id', $this->selectedCategory))
            ->paginate(12);

        return view('livewire.online-store', [
            'products' => $products,
            'categories' => Category::all(),
        ])->layout('layouts.guest'); // Ensure this matches your layout with the header
    }
}