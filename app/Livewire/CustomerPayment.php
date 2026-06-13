<?php

namespace App\Livewire;

use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CustomerPayment extends Component
{
    public $search = '';
    public $selected_customer = null;
    public $payment_amount = 0;

    public function selectCustomer($id)
    {
        $this->selected_customer = Customer::find($id);
        $this->search = ''; 
    }

    public function recordPayment()
    {
        if (!$this->selected_customer || $this->payment_amount <= 0) {
            return;
        }

        DB::transaction(function () {
            Payment::create([
                'customer_id' => $this->selected_customer->id,
                'amount' => $this->payment_amount,
                'method' => 'cash',
            ]);

            $customer = Customer::find($this->selected_customer->id);
            $customer->decrement('current_balance', $this->payment_amount);

            // Give 1 point for every $1 paid
            $newPoints = floor($this->payment_amount); 
            
            $customer->increment('total_purchases', $this->payment_amount);
            $customer->increment('points', $newPoints);
        });

        $this->reset(['payment_amount', 'selected_customer', 'search']);
        session()->flash('message', 'Payment added to total and points updated!');
    }

    public function recalculateAllPoints()
    {
        foreach (Customer::all() as $customer) {
            $customer->update([
                'points' => floor($customer->total_purchases)
            ]);
        }
        session()->flash('message', 'All customer points have been synchronized!');
    }

    public function printReceipt($customerId)
    {
        // This finds the LATEST single payment for this customer to print
        $payment = Payment::where('customer_id', $customerId)->latest()->first();
        
        if (!$payment) return;

        $pdf = Pdf::loadView('pdf.payment-receipt', ['payment' => $payment]);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'receipt-customer-' . $customerId . '.pdf');
    }

    public function redeemPoints()
    {
        if (!$this->selected_customer || $this->selected_customer->points < 100) {
            session()->flash('error', 'Customer needs at least 100 points to redeem!');
            return;
        }
    
        DB::transaction(function () {
            $customer = Customer::find($this->selected_customer->id);
            
            // Logic: 100 points = $5 discount
            $discountAmount = 5;
            $pointsToDeduct = 100;
    
            $customer->decrement('points', $pointsToDeduct);
            $customer->decrement('current_balance', $discountAmount);
            
            // Record this as a "Point Redemption" in your payments table for tracking
            \App\Models\Payment::create([
                'customer_id' => $customer->id,
                'amount' => $discountAmount,
                'method' => 'points_redemption',
            ]);
        });
    
        // Refresh the selected customer data to show new balance/points
        $this->selected_customer = Customer::find($this->selected_customer->id);
        session()->flash('message', '100 points redeemed for a $5 discount!');
    }

    public function render()
    {
        $customers = [];
        if (strlen($this->search) > 0) {
            $customers = Customer::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->get();
        }

        $historyRecords = Payment::with('customer')
            ->select('customer_id', DB::raw('SUM(amount) as total_paid'))
            ->groupBy('customer_id')
            ->get();

        return view('livewire.customer-payment', [
        'customers' => $customers,
        'historyRecords' => $historyRecords 
    ])->layout('admin.dashboard'); // <--- ADD THIS LINE
    }
}