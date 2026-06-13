<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf; 

class ManageOrders extends Component
{
    public $printOrder = null;

    public function markAsCompleted($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => 'completed']);
            session()->flash('message', 'Order marked as completed!');
        }
    }

    public function selectPrintOrder($orderId)
    {
        $this->printOrder = Order::find($orderId);
        $this->dispatch('open-print-modal');
    }

    public function deleteOrder($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->delete();
            session()->flash('message', 'Order deleted successfully.');
        }
    }

    /**
     * PUBLIC method for Admin Dashboard buttons
     * (Called by the PDF buttons in your table)
     */
    public function downloadPDF($orderId)
    {
        return self::generatePDF($orderId);
    }

    /**
     * STATIC method for the Thank You page and Routes
     * (Handles the actual PDF creation)
     */
    public static function generatePDF($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return abort(404, 'Order not found');
        }

        // Use the view in resources/views/pdf/order-recipe.blade.php
        $pdf = Pdf::loadView('pdf.order-recipe', ['printOrder' => $order]);
        
        // Thermal paper size (80mm width)
        $pdf->setPaper([0, 0, 226, 600]);

        return $pdf->download('Order-'.$order->order_number.'.pdf');
    }

    public function render()
    {
        return view('livewire.manage-orders', [
            'orders' => Order::orderBy('id', 'desc')->get()
        ])->layout('admin.dashboard');
    }
}