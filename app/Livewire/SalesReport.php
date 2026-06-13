<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class SalesReport extends Component
{
    public $filter = 'today'; 
    public $selectedDate;
    public $selectedSale = null; // Defined ONLY once
    public $qrCode = null;       // Defined ONLY once
    public $search = ''; // Add this property for searching
    // Helper for data filtering
    
private function getFilteredQuery()
{
    $query = Sale::with(['items.product', 'customer']);

    // Filter ONLY by Customer Name or Phone Number
    if (!empty($this->search)) {
        $query->whereHas('customer', function($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('phone', 'like', '%' . $this->search . '%');
        });
    }

    // Apply existing Date/Category Filters
    if ($this->filter === 'today') {
        $query->whereDate('created_at', Carbon::today());
    } elseif ($this->filter === 'weekly') {
        $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
    } elseif ($this->filter === 'monthly') {
        $query->whereMonth('created_at', Carbon::now()->month)
              ->whereYear('created_at', Carbon::now()->year);
    } elseif ($this->filter === 'custom' && $this->selectedDate) {
        $query->whereDate('created_at', $this->selectedDate);
    }

    return $query;
}

    // Opens the Popup and generates the QR code
    public function viewSale($id)
    {
        $this->selectedSale = Sale::with(['items.product', 'customer'])->find($id);
        
        // Generate QR Code for the popup
        $qrData = "QUICKSPACE POS\nReceipt: #{$id}\nTotal: \${$this->selectedSale->total_amount}";
        $qrCodeData = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(150)
            ->errorCorrection('H')
            ->generate($qrData);
            
        $this->qrCode = base64_encode($qrCodeData);

        $this->dispatch('open-sale-modal');
    }

    // Handles single receipt download from the popup
    public function downloadReceipt($id)
    {
        // This calls the existing print logic in your PosSystem component
        return app(PosSystem::class)->printSaleReceipt($id);
    }

    // Exports the current list as a PDF report
    public function exportPdf()
    {
        try {
            $sales = $this->getFilteredQuery()->get();
            $pdf = Pdf::loadView('pdf.sales-reports', ['sales' => $sales]);

            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->stream();
            }, "Sales_Report_" . now()->format('Y-m-d') . ".pdf");

        } catch (\Exception $e) {
            dd("PDF Error: " . $e->getMessage());
        }
    }
    
    // Exports the current list as CSV/Excel
    public function exportExcel()
    {
        try {
            $sales = $this->getFilteredQuery()->get();
            $filename = "sales_report_" . now()->format('Y-m-d') . ".csv";

            return response()->streamDownload(function() use ($sales) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Sale ID', 'Date/Time', 'Customer', 'Phone', 'Items', 'Subtotal', 'Discount', 'Total Paid']);

                foreach ($sales as $sale) {
                    $rawSubtotal = $sale->items->sum(fn($item) => $item->price * $item->quantity);
                    $discount = $rawSubtotal - $sale->total_amount;
                    $items = $sale->items->map(fn($i) => $i->product->name . "(x".$i->quantity.")")->implode('; ');

                    fputcsv($file, [
                        "#" . $sale->id,
                        $sale->created_at->format('d-M-Y h:i A'),
                        $sale->customer->name ?? 'Walk-in',
                        " " . ($sale->customer->phone ?? 'N/A'),
                        $items,
                        number_format($rawSubtotal, 2, '.', ''),
                        number_format($discount, 2, '.', ''),
                        number_format($sale->total_amount, 2, '.', '')
                    ]);
                }
                fclose($file);
            }, $filename);

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function render()
    {
        $sales = $this->getFilteredQuery()->latest()->get();
        $totalRevenue = $sales->sum('total_amount');

        return view('livewire.sales-report', [
            'sales' => $sales,
            'totalRevenue' => $totalRevenue
        ])->layout('admin.dashboard'); 
    }
}