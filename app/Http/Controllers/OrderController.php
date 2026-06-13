<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function downloadReceipt($id)
    {
        $order = Order::findOrFail($id);
        
        // This targets your file: resources/views/pdf/sale-receipt.blade.php
        $pdf = Pdf::loadView('pdf.sale-receipt', compact('order'));
        
        return $pdf->stream('receipt-'.$order->order_number.'.pdf');
    }
}