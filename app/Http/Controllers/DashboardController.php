<?php

namespace App\Http\Controllers; // CRITICAL: This was missing

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Needed for dashboard stats
use Carbon\Carbon; // Ensure this is present for the date logic

class DashboardController extends Controller
{
    /**
     * Show the Dashboard with Stats
     */
    public function index()
{
    // 1. Stats Section (Keep your existing code)
    $todaySales = (float) DB::table('sales')->whereDate('created_at', date('Y-m-d'))->sum('total_amount') ?: 0.00;
    $totalCustomers = (int) DB::table('customers')->count() ?: 0;
    
    $lowStockCount = (int) DB::table('products')->where('stock', '<', 10)->count() ?: 0;

    // 2. Weekly Sales Chart Logic (Add this here)
    $startOfWeek = \Carbon\Carbon::now()->startOfWeek(); 
    $endOfWeek = \Carbon\Carbon::now()->endOfWeek();

    $weeklySales = DB::table('sales')
        ->select(
            DB::raw('DATE(created_at) as date'), 
            DB::raw('SUM(total_amount) as total') 
        )
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->groupBy('date')
        ->get()
        ->pluck('total', 'date');

    $labels = [];
    $values = [];

    for ($i = 0; $i < 7; $i++) {
        $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
        $dayName = $startOfWeek->copy()->addDays($i)->format('D'); 
        
        $labels[] = $dayName;
        $values[] = (float) ($weeklySales[$date] ?? 0);
    }

    // 3. Pie Chart Data (Keep your existing code)
    $topProducts = DB::table('sale_items')
        ->join('products', 'sale_items.product_id', '=', 'products.id')
        ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_qty'))
        ->groupBy('products.id', 'products.name')
        ->orderBy('total_qty', 'desc')
        ->take(5)
        ->get();

    $pieLabels = $topProducts->pluck('name');
    $pieValues = $topProducts->pluck('total_qty');

  

$recentSales = DB::table('sales')
        ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id')
        ->select(
            'sales.id', 
            'sales.total_amount', 
            'sales.created_at', 
            'customers.name as customer_name'
        )
        ->orderBy('sales.created_at', 'desc')
        ->take(5)
        ->get();

    // 2. Add 'recentSales' to the compact list below
    return view('admin.dashboard', compact(
        'todaySales', 
        'totalCustomers', 
        'lowStockCount', 
        'labels', 
        'values', 
        'pieLabels', 
        'pieValues',
        'recentSales' // <--- DO NOT FORGET THIS LINE
    ));
}
    public function handleRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function handleLogin(Request $request)
{
    $credentials = $request->only('email', 'password');
    
    // The 'true' here is what activates the "Remember Me" system
    if (Auth::attempt($credentials, true)) { 
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
}
}

