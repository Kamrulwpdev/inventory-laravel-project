<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Category;
use Livewire\WithFileUploads;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PosSystem extends Component
{
    use WithFileUploads;

    // Search and Selection Properties
    public $search = ''; 
    public $search_product = '';
    public $selected_category = null;
    public $barcode = '';

    // Customer Properties
    public $customers = [];
    public $customer_id = null;
    public $new_customer_name = '';
    public $new_customer_phone = '';

    // Cart and Pricing Properties
    public $cart = [];
    public $discount_type = 'fixed'; 
    public $manual_discount = 0;           // Clerk entered value
    public $points_redemption_value = 0;   // Loyalty points value
    public $discount_amount = 0;           // Calculated manual total

    public function mount()
    {
        $this->customers = Customer::all();
    }

    /**
     * Discount Calculation Logic
     * Updated to handle both Manual Discount and Loyalty Redemption safely.
     */
    public function updatedManualDiscount()
    {
        $this->calculateDiscount();
    }

    public function updatedDiscountType()
    {
        $this->calculateDiscount();
    }

    public function calculateDiscount()
    {
        $subtotal = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
        
        // 1. Calculate Manual Discount based on type
        if ($this->discount_type === 'percentage') {
            $this->discount_amount = ($subtotal * (float)$this->manual_discount) / 100;
        } else {
            $this->discount_amount = (float)$this->manual_discount;
        }

        // 2. Safety Check: Total Due cannot be negative
        $potentialTotal = $subtotal - $this->discount_amount - $this->points_redemption_value;
        
        if ($potentialTotal < 0) {
            // Cap the manual discount so the Final Total is exactly 0.00
            $this->discount_amount = max(0, $subtotal - $this->points_redemption_value);
        }
    }

    /**
     * Set redemption intent. 
     * Points are only officially deducted during checkout.
     */
    public function redeemPoints()
    {
        $customer = Customer::find($this->customer_id);
        if ($customer && $customer->points >= 100) {
            $this->points_redemption_value = 5.00;
            $this->calculateDiscount(); 
            
            $this->dispatch('play-success-sound');
            session()->flash('message', '$5 Loyalty Discount Applied!');
        } else {
            session()->flash('error', 'Insufficient points for redemption.');
        }
    }

    /**
     * Cart Management
     */
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'Product out of stock!');
            return;
        }

        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
        } else {
            $this->cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
            ];
        }
        $this->calculateDiscount(); 
        $this->dispatch('play-beep');
        $this->dispatch('item-added');
    }

    public function updatedBarcode()
    {
        $product = Product::where('barcode', $this->barcode)->first();
        if ($product) {
            $this->addToCart($product->id);
        }
        $this->barcode = ''; 
    }

    public function removeItem($id) 
    { 
        unset($this->cart[$id]); 
        $this->calculateDiscount();
        $this->dispatch('play-remove-sound'); 
    }
    
    public function updateQuantity($id)
    {
        if (!isset($this->cart[$id])) return;

        $newQty = intval($this->cart[$id]['qty']);

        if ($newQty < 1) {
            $this->cart[$id]['qty'] = 1;
        } else {
            $this->cart[$id]['qty'] = $newQty;
        }

        $this->calculateDiscount();
    }

    /**
     * Sales & Checkout Transaction
     */
    public function checkout()
    {
        if (empty($this->cart)) return;
        $saleId = null;

        $this->calculateDiscount();

        DB::transaction(function () use (&$saleId) {
            $subtotal = collect($this->cart)->sum(fn($item) => $item['price'] * $item['qty']);
            $finalTotal = $subtotal - $this->discount_amount - $this->points_redemption_value;

            $sale = Sale::create([
                'total_amount' => $finalTotal,
                'manual_discount_amount' => $this->discount_amount, 
                'redemption_discount_amount' => $this->points_redemption_value,
                'user_id' => 1, // Use auth()->id() in production
                'customer_id' => $this->customer_id,
                'payment_status' => $this->customer_id ? 'due' : 'paid',
            ]);

            $saleId = $sale->id;

            foreach ($this->cart as $item) {
                $sale->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
                Product::find($item['id'])->decrement('stock', $item['qty']);
            }

            if ($this->customer_id) {
                $customer = Customer::find($this->customer_id);
                
                // Finalize Point Deduction if reward was used
                if ($this->points_redemption_value > 0) {
                    $customer->decrement('points', 100);
                }

                $customer->increment('current_balance', $finalTotal);
                $customer->increment('points', floor($finalTotal));
                $customer->increment('total_purchases', $finalTotal);
            }
        });

        $this->reset(['cart', 'customer_id', 'search', 'discount_amount', 'manual_discount', 'discount_type', 'points_redemption_value']);
        session()->flash('message', 'Sale recorded!');
        return $this->printSaleReceipt($saleId);
    }

    public function registerCustomer()
    {
        $this->validate([
            'new_customer_name' => 'required|string|max:255',
            'new_customer_phone' => 'required|unique:customers,phone',
        ]);

        $customer = Customer::create([
            'name' => $this->new_customer_name,
            'phone' => $this->new_customer_phone,
        ]);

        $this->updatedSearch(); 
        $this->customer_id = $customer->id;
        $this->reset(['new_customer_name', 'new_customer_phone']);
        session()->flash('message', 'New customer registered and selected!');
    }

    public function updatedSearch()
    {
        $this->customers = Customer::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->get();
    }

    public function printSaleReceipt($saleId)
    {
        $sale = Sale::with(['items.product', 'customer'])->findOrFail($saleId);
        
        $total = number_format($sale->total_amount, 2);
        $customerName = $sale->customer->name ?? 'Walk-in';

        $qrData = "QUICKSPACE POS\n" .
                  "Receipt: #{$saleId}\n" .
                  "Total: \${$total}\n" .
                  "Date: " . $sale->created_at->format('Y-m-d');

        $qrCodeData = QrCode::format('svg')->size(150)->errorCorrection('H')->generate($qrData);
        $base64Qr = base64_encode($qrCodeData);

        $pdf = Pdf::loadView('pdf.sale-receipt', [
            'sale' => $sale,
            'qrCode' => $base64Qr
        ]);
        
        return response()->streamDownload(fn() => print($pdf->stream()), 'receipt-'.$saleId.'.pdf');
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search_product, function($query) {
                $query->where('name', 'like', '%' . $this->search_product . '%')
                      ->orWhere('barcode', 'like', '%' . $this->search_product . '%');
            })
            ->when($this->selected_category, function($query) {
                $query->where('category_id', $this->selected_category);
            })
            ->get();

        return view('livewire.pos-system', [
            'selectedCustomer' => $this->customer_id ? Customer::find($this->customer_id) : null,
            'products' => $products, 
            'categories' => Category::all()
        ])->layout('admin.dashboard');
    }
}