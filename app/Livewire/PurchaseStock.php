<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class PurchaseStock extends Component
{
    // These properties connect to your form inputs via wire:model
    public $selected_product;
    public $qty_bought;
    public $cost;

    public function storePurchase()
    {
        // Validation ensures the owner doesn't submit empty data
        $this->validate([
            'selected_product' => 'required',
            'qty_bought' => 'required|numeric|min:1',
            'cost' => 'required|numeric',
        ]);

        DB::transaction(function () {
            // 1. Create the purchase record for history tracking
            Purchase::create([
                'product_id' => $this->selected_product,
                'quantity' => $this->qty_bought,
                'purchase_price' => $this->cost,
            ]);

            // 2. Automatically update product stock in the products table
            $product = Product::find($this->selected_product);
            $product->increment('stock', $this->qty_bought);
        });

        // Reset the form after success
        $this->reset(['selected_product', 'qty_bought', 'cost']);
        
        session()->flash('message', 'Stock updated successfully!');
    }

    public function render()
{
    return view('livewire.purchase-stock', [
        'products' => Product::all()
    ])->layout('admin.dashboard'); // <--- Change 'layouts.app' to match your actual main layout file name
}
}