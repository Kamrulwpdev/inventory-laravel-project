<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\SalesReport;
use App\Livewire\PosSystem;
use App\Livewire\Checkout;
use App\Livewire\CustomerPayment;
use App\Livewire\ProductList;
use App\Livewire\UserManagement; // Ensure this exists
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Livewire\OnlineStore\OnlineStore;
use Illuminate\Support\Facades\Artisan;
use App\Livewire\ManageOrders;
use App\Http\Controllers\OrderController; // Create this controller if you don't have it
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\SaleController; // Or wherever your print logic lives
use App\Livewire\OnlineStore\ProductDetails;
use App\Livewire\OnlineStore\HomePage;
use App\Livewire\OnlineStore\ShopPage;
use App\Livewire\OnlineStore\SearchResultPage;
use App\Livewire\OnlineStore\CategoryPage;
// Fix this line in web.php
Route::get('/category', \App\Livewire\OnlineStore\ShopPage::class)->name('shop.page');
Route::get('/category/{slug}', CategoryPage::class)->name('category.show');
// Add this line
Route::get('/search', SearchResultPage::class)->name('search.results');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'bn'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');
// Replace 'Checkout::class' with your actual Checkout component if you have one
Route::get('/checkout', Checkout::class)->name('checkout');
// In routes/web.php

// Make sure the name is exactly 'sales.pdf'
Route::get('/print-receipt/{id}', [PosSystem::class, 'printSaleReceipt'])->name('sales.pdf');
// Add this at the top of web.php

// --- PUBLIC ROUTES ---
// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/', \App\Livewire\OnlineStore\HomePage::class)->name('home');
Route::get('/home', \App\Livewire\OnlineStore\HomePage::class);
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
// Find your existing download route and update it to look like this:
// Only keep this one for downloading:
// Update this line to use the new static name 'generatePDF'
// web.php

// Add this route
Route::get('/generate-pdf/{id}', [OrderController::class, 'downloadReceipt'])->name('sales.pdf');

Route::get('/admin/orders/download/{orderId}', [ManageOrders::class, 'generatePDF'])->name('admin.orders.download');
// Route::get('/admin/orders/download/{orderId}', [ManageOrders::class, 'downloadPDF'])->name('admin.orders.download');
Route::get('/order-success/{orderId}', \App\Livewire\OrderSuccess::class)->name('order.success');
// Public Route (No 'auth' middleware)
Route::get('/admin/orders', \App\Livewire\ManageOrders::class)->middleware('auth');
Route::get('/sales-history', \App\Livewire\SalesHistory::class)->name('sales.history');
Route::get('/purchase', \App\Livewire\PurchaseStock::class)->name('purchase.index');
// Route::get('/home', \App\Livewire\OnlineStore::class)->name('home');
Route::get('/shop', \App\Livewire\OnlineStore\ShopPage::class)->name('shop.page');
// routes/web.php
// Route::get('/product/{id}', ProductDetails::class)->name('product.details');
Route::get('/product/{product}', \App\Livewire\OnlineStore\ProductDetails::class)->name('product.details');
// Route::get('/product/{product:slug}', \App\Livewire\OnlineStore\ProductDetails::class);
Route::post('/login', [DashboardController::class, 'handleLogin']);
// Existing Online Store (The one with the hero section and slider)
Route::get('/online-store', \App\Livewire\OnlineStore\OnlineStore::class)->name('online.store');

// New Shop Page (The professional catalog style)
// Route::get('/online-store', \App\Livewire\OnlineStore\ShopPage::class)->name('shop.page');
// Route::get('/register', function () { 
//     return view('auth.register'); 
// })->name('register');

// Route::post('/register', [DashboardController::class, 'handleRegister']);

// --- PROTECTED ROUTES (Require Login) ---
Route::middleware(['auth'])->group(function () {

    // 1. ADMIN ONLY: User Management
    Route::middleware(['role:admin,shop_manager,account_manager'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', UserManagement::class)->name('users.index');
    });

    // 2. ADMIN & SHOP MANAGER: Product Management
    Route::middleware(['role:admin,shop_manager'])->group(function () {
        Route::get('/products', ProductList::class)->name('products.index');
    });

    // 3. EVERYONE (Admin, Shop Manager, Account Manager): POS, Payments & Reports
    Route::middleware(['role:admin,shop_manager,account_manager'])->group(function () {
        Route::get('/pos', PosSystem::class)->name('pos');
        Route::get('/payments', CustomerPayment::class)->name('payments');
        Route::get('/reports', SalesReport::class)->name('reports');
    });


    // --- LOGOUT ---
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});


Route::get('/fixstorage', function () {
    Artisan::call('storage:link');
    return "Storage link created successfully!";
});

// --- UTILITY ---
Route::get('/clear-everything', function() {
    \Artisan::call('view:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    return "✅ All caches cleared!";
});