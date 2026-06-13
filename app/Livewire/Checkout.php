<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class Checkout extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;
    public $delivery_charge = 0; // Added delivery charge property
    
    // Form fields
    public $customer_name, $customer_phone, $customer_address;

    public function mount()
    {
        $this->cartItems = session()->get('cart', []);
        
        if (empty($this->cartItems)) {
            return redirect()->to('/shop');
        }

        $this->calculateTotal();
    }

    // ONLY ONE VERSION OF THIS FUNCTION IS ALLOWED
    public function calculateTotal()
    {
        $subtotal = collect($this->cartItems)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        // Add delivery charge to the total
        $this->cartTotal = $subtotal + (float)$this->delivery_charge;
    }

    // This refreshes the total whenever you select a delivery area
    public function updatedDeliveryCharge()
    {
        $this->calculateTotal();
    }

    public function placeOrder()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'delivery_charge' => 'required|numeric|min:1', // Ensures they pick an area
        ], [
            'delivery_charge.min' => 'Please select a delivery area.'
        ]);

        $newOrder = Order::create([
            'order_number' => 'QS-' . strtoupper(uniqid()),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_address' => $this->customer_address,
            'total_amount' => $this->cartTotal,
            'items' => $this->cartItems,
            'status' => 'pending'
        ]);
    
        session()->forget('cart');
        
        // Redirect to success page with the new Order ID
        return redirect()->to('/order-success/' . $newOrder->id);
    }

    public function render()
    {
        return view('livewire.checkout')->layout('layouts.guest');
    }
}