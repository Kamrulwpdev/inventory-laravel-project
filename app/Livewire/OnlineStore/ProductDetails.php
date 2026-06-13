<?php

namespace App\Livewire\OnlineStore;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductDetails extends Component
{
    public $product;
    public $quantity = 1;
    public $showCart = false;
    public $cartItems = [];
    public $cartCount = 0;
    public $cartTotal = 0;


// Ensure this matches the {slug} in your route
// Use the Model type-hint with the variable name $product
public function mount(\App\Models\Product $product) 
{
    $this->product = $product->load('category');
    $this->updateCartInfo();
}

    public function updateCartInfo()
    {
        $this->cartItems = session()->get('cart', []);
        $this->cartCount = array_sum(array_column($this->cartItems, 'quantity'));
        $this->cartTotal = array_reduce($this->cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function increment()
    {
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        $cart = session()->get('cart', []);
        $productId = $this->product->id;
        
        if(isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $this->quantity;
        } else {
            $cart[$productId] = [
                "name" => $this->product->name,
                "quantity" => $this->quantity,
                "price" => $this->product->price,
                "image" => $this->product->image
            ];
        }

        session()->put('cart', $cart);
        $this->updateCartInfo();
        $this->showCart = true; 
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->updateCartInfo();
        }
    }

    public function getImageUrl()
    {
        $path = $this->product->image;
        if (!$path) return asset('images/no-image.png');

        if (Str::startsWith($path, 'image/')) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }

    public function render()
    {
        return view('livewire.online-shop.product-details', [
            'productImage' => $this->getImageUrl()
        ])->layout('layouts.guest');
    }
}