<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderSuccess extends Component
{
    public $order;

    public function mount($orderId)
    {
        // Find the order by the ID passed in the URL
        $this->order = Order::findOrFail($orderId);
    }

    public function render()
    {
        return view('livewire.order-success')->layout('layouts.guest');
    }
}