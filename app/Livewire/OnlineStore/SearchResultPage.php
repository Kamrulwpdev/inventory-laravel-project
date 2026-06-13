<?php

namespace App\Livewire\OnlineStore;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class SearchResultPage extends Component
{
    use WithPagination;
    public $query;

    public function mount()
    {
        $this->query = request()->query('query', '');
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->query . '%')
            ->paginate(12);

        return view('livewire.online-shop.search-result-page', [
            'products' => $products
        ])->layout('layouts.guest');
    }
}

