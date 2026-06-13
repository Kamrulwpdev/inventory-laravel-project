<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesChart extends Component
{
    public $chartData = [];

    public function mount()
{
    // Load monthly sales immediately so chart has data on page load
    $this->getMonthlySales();
}

   public function getMonthlySales()
{
    // Use 'total_amount' as seen in your database screenshot
    $sales = DB::table('sales')
        ->select(
            DB::raw('MONTH(created_at) as month'), 
            DB::raw('SUM(total_amount) as total') // Fixed column name
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    // Prepare the 12 months array
    $monthlyData = array_fill(1, 12, 0);

    foreach ($sales as $sale) {
        $monthlyData[$sale->month] = (float)$sale->total;
    }

    $this->chartData = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        'data' => array_values($monthlyData),
        'title' => 'Monthly Sales (' . date('Y') . ')'
    ];

    // Dispatch for Livewire v3 to update the Chart.js instance
    $this->dispatch('update-chart-data', 
        labels: $this->chartData['labels'],
        data: $this->chartData['data'],
        title: $this->chartData['title']
    );
}

    public function getYearlySales()
{
    $sales = DB::table('sales')
        ->select(
            DB::raw('YEAR(created_at) as year'), 
            DB::raw('SUM(total_amount) as total') // Fixed column name
        )
        ->groupBy('year')
        ->orderBy('year', 'asc')
        ->get();

    $this->chartData = [
        'labels' => $sales->pluck('year')->toArray(),
        'data' => $sales->pluck('total')->map(fn($val) => (float)$val)->toArray(),
        'title' => 'Yearly Sales Performance'
    ];

    $this->dispatch('update-chart-data', 
        labels: $this->chartData['labels'],
        data: $this->chartData['data'],
        title: $this->chartData['title']
    );
}

    public function render()
    {
        return view('livewire.sales-chart');
    }
}