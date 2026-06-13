<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;

class SalesHistory extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedOrder; 
    protected $paginationTheme = 'bootstrap';

    // Ensure search resets pagination
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewDetails($orderId)
    {
        // Use findOrFail to prevent errors if the order is missing
        $this->selectedOrder = Order::findOrFail($orderId);
        $this->dispatch('show-order-modal');
    }

    public function deleteSale($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            session()->flash('message', 'Sale record deleted successfully.');
        }
    }

    public function render()
    {
        
        // Fixing the 'order_id' vs 'order_number' column issue
        $sales = Order::where('order_number', 'like', '%' . $this->search . '%')
            ->orWhere('customer_name', 'like', '%' . $this->search . '%')
            ->orWhere('customer_phone', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.sales-history', [
            'sales' => $sales
        ])->layout('admin.dashboard');
    }
}