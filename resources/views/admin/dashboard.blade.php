<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quickspace POS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @livewireStyles

    <style>
        :root { --purple-main: #b66dff; --sidebar-width: 260px; }
        body { background-color: #f2edf3; font-family: "ubuntu-regular", sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        #sidebar {
            background: #ffffff;
            width: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
            position: fixed;
            z-index: 100;
        }

        .sidebar-brand { padding: 1.5rem 2rem; }
        
        .sidebar-profile {
            padding: 0.5rem 2rem 1.5rem;
            display: flex;
            align-items: center;
        }

        .profile-name { margin-left: 15px; line-height: 1.2; }
        .profile-name h5 { font-size: 14px; margin-bottom: 2px; font-weight: 600; color: #343a40; }
        .profile-name span { font-size: 12px; color: #8c8c8c; }

        .nav-category {
            font-size: 11px;
            font-weight: 600;
            color: #444;
            padding: 15px 25px 5px;
            text-transform: uppercase;
        }

        .sidebar-nav .nav-link {
            color: #3e4b5b;
            padding: 0.75rem 2.25rem;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar-nav .nav-link i { color: var(--purple-main); width: 30px; font-size: 1.1rem; }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active { background-color: #f2edf3; color: var(--purple-main); }
        /*.sidebar-nav .nav-link.active { color: var(--purple-main); font-weight: 600; }*/

        /* Main Content Logic */
        #content-wrapper {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s ease;
        }

        /* Top Header */
        .top-navbar {
            height: 70px;
            background: #fff;
            padding: 0 2.5rem;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        /* Purple Admin Gradient Cards */
        .bg-gradient-primary { background: linear-gradient(to right, #da8cff, #9a55ff) !important; }
        .bg-gradient-danger { background: linear-gradient(to right, #5346e6, #5346e6) !important; }
        .bg-gradient-info { background: linear-gradient(to right, #90caf9, #047edf) !important; }
        
        .card.card-img-holder { position: relative; overflow: hidden; border-radius: 10px; }
        
        /* The decorative circle overlay (Cite: image_50351f.jpg) */
        .card.card-img-holder::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 160px;
            height: 160px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(25%, -25%);
        }

        /* Minimize Toggle States */
        body.sidebar-toggled #sidebar { width: 70px; }
        body.sidebar-toggled #content-wrapper { margin-left: 70px; width: calc(100% - 70px); }
        body.sidebar-toggled .sidebar-brand span, 
        body.sidebar-toggled .sidebar-profile,
        body.sidebar-toggled .nav-link span,
        body.sidebar-toggled .nav-category { display: none !important; }
        body.sidebar-toggled .nav-link { justify-content: center; padding: 20px 0; }
        body.sidebar-toggled .nav-link i { margin: 0; }
    </style>
</head>
<body>

<div class="d-flex">
    <nav id="sidebar" class="shadow-sm">
        <div class="sidebar-brand">
            <a class="text-decoration-none fw-bold fs-4" href="{{ route('dashboard') }}" style="color: var(--purple-main);">
                <i class="bi bi-layers-fill"></i> <span>QuickSpace</span>
            </a>
        </div>

        <div class="sidebar-profile">
            <img class="rounded-circle border" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=da8cff&color=fff" width="45">
            <div class="profile-name">
                <h5 class="mb-0 text-truncate" style="max-width: 120px;">{{ auth()->user()->name }}</h5>
                <span>Project Manager</span>
            </div>
            <i class="bi bi-check-circle-fill text-success ms-auto small"></i>
        </div>

        <div class="sidebar-nav">
            <div class="nav-category">Main</div>
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door-fill"></i> <span>Dashboard</span>
            </a>
            
            <a class="nav-link {{ request()->is('pos') ? 'active' : '' }}" href="/pos">
                <i class="bi bi-cart-fill"></i> <span>POS Screen</span>
            </a>
            
            <a class="nav-link {{ request()->is('reports') ? 'active' : '' }}" href="/reports">
                <i class="bi bi-bar-chart-fill"></i> <span>Sales History</span>
            </a>
            <a class="nav-link {{ request()->is('payments') ? 'active fw-bold' : '' }}" href="/payments">
                <i class="fas fa-money-bill-wave"></i> <span>Collect Payments</span>
            </a>
            
             <a class="nav-link" href="{{ route('online.store') }}" target="_blank">
                <i class="bi bi-shop me-2"></i> <span> Online Store</span>
        
            
            <!--<a href="{{ route('sales.history') }}" class="nav-link">-->
            <!--    <i class="bi bi-journal-text"></i> <span>Sales History</span>-->
            <!--</a>-->

            @if(Auth::user()->role == 'admin')
                <a href="{{ route('purchase.index') }}" class="nav-link">
                    <i class="bi bi-cart-plus"></i> <span>Stock In</span>
                </a>
                <a class="nav-link {{ request()->is('admin/orders') ? 'active' : '' }}" href="{{ url('/admin/orders') }}">
                    <i class="bi bi-cart-check"></i> <span>Orders</span>
                </a>
                <a class="nav-link {{ request()->is('products') ? 'active' : '' }}" href="/products">
                    <i class="bi bi-box-seam-fill"></i> <span>Products</span>
                </a>
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people-fill"></i> <span>Users</span>
                </a>
            @endif
        </div>
    </nav>

    <div id="content-wrapper">
        <header class="top-navbar d-flex justify-content-between align-items-center shadow-sm">
            <div class="d-flex align-items-center">
                <button id="sidebarToggle" class="btn btn-link p-0 me-4 text-muted">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-none d-md-flex align-items-center bg-light px-3 py-1 rounded-pill border">
                    <i class="bi bi-search text-muted small me-2"></i>
                    <input type="text" class="form-control form-control-sm border-0 bg-transparent" placeholder="Search tasks..." style="box-shadow: none;">
                </div>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="text-decoration-none text-dark d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <img class="rounded-circle me-2 border" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" width="32">
                        <span class="fw-600 small d-none d-lg-inline">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3">
                        <li>
                            <form method="POST" action="/logout">
                                @csrf
                                <button class="dropdown-item text-danger py-2" type="submit">
                                    <i class="bi bi-power me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="container-fluid p-4">
            @isset($totalCustomers)
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card bg-gradient-danger card-img-holder text-white border-0 shadow-sm">
                        <div class="card-body py-4">
                            <h4 class="fw-normal mb-3">Today Sales <i class="bi bi-graph-up float-end opacity-50"></i></h4>
                            <h2 class="mb-3 fw-bold">${{ number_format($todaySales ?? 0, 2) }}</h2>
                            <p class="mb-0 small opacity-75">Increased by 60%</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-gradient-info card-img-holder text-white border-0 shadow-sm">
                        <div class="card-body py-4">
                            <h4 class="fw-normal mb-3">Total Customers <i class="bi bi-bookmark-star float-end opacity-50"></i></h4>
                            <h2 class="mb-3 fw-bold">{{ number_format($totalCustomers) }}</h2>
                            <p class="mb-0 small opacity-75">Decreased by 10%</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-gradient-primary card-img-holder text-white border-0 shadow-sm">
                        <div class="card-body py-4">
                            <h4 class="fw-normal mb-3">Low Stock Products<i class="bi bi-diamond float-end opacity-50"></i></h4>
                            <h2 class="mb-3 fw-bold">{{ $lowStockCount }}</h2>
                            <p class="mb-0 small opacity-75">Increased by 5%</p>
                        </div>
                    </div>
                </div>
            </div>
            @endisset

            <div class="mt-2">
                {{ $slot ?? '' }}
            </div>

            @isset($labels)
            <div class="row mt-4 g-2">
                <div class="col-xl-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3 bg-white fw-bold border-bottom-0">Weekly Sales Statistics</div>
                        <div class="card-body">
                            <div style="height: 320px;"><canvas id="dailySalesTrendChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3 bg-white fw-bold border-bottom-0">Top Sales Products</div>
                        <div class="card-body">
                            <div style="height: 320px;"><canvas id="productPieChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--recen tsales hostory tables-->
            <div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Recent Transactions</h5>
                    <a href="/reports" class="btn btn-sm btn-light text-primary rounded-pill px-3">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 text-muted small text-uppercase">Sale ID</th>
                                <th class="border-0 text-muted small text-uppercase">Customer</th>
                                <th class="border-0 text-muted small text-uppercase">Date & Time</th>
                                <th class="border-0 text-muted small text-uppercase text-end">Amount</th>
                                <th class="border-0 text-muted small text-uppercase text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="ps-4 fw-bold">#{{ $sale->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 12px; font-weight: 600;">
                                                {{ strtoupper(substr($sale->customer_name ?? 'W', 0, 1)) }}
                                            </div>
                                            <span>{{ $sale->customer_name ?? 'Walk-in Customer' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">
                                        {{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        ${{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <span class="badge rounded-pill bg-soft-success text-success px-3" style="font-weight: 500;">
                                            Completed
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No recent transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling to match your Dashboard theme */
    .bg-soft-primary { background-color: #eef2ff !important; }
    .bg-soft-success { background-color: #ecfdf5 !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .table thead th { font-size: 0.75rem; letter-spacing: 0.025em; padding: 1rem 0.5rem; }
    .table tbody td { padding: 1rem 0.5rem; border-color: #f8fafc; }
    .table-hover tbody tr:hover { background-color: #fcfdfe; }
</style>
            
            <div class="row">
                <div class="col-12 mt-4">
                    @livewire('sales-chart')
                </div>
            </div>
            @endisset
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@livewireScripts

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('sidebarToggle');
    if(btn) {
        btn.addEventListener('click', () => document.body.classList.toggle('sidebar-toggled'));
    }

    @isset($labels)
    // Sales Chart (Cite: image_50351f.jpg)
    const salesCtx = document.getElementById('dailySalesTrendChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($values) !!},
                borderColor: '#b66dff', 
                backgroundColor: 'rgba(182, 109, 255, 0.1)',
                fill: true,
                borderWidth: 3,
                tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    // Pie Chart with Safety Check (Cite: image_502185.jpg)
    const pieCtx = document.getElementById('productPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($pieLabels ?? ['No Data']) !!},
            datasets: [{
                data: {!! json_encode($pieValues ?? [1]) !!},
                backgroundColor: ['#fe7096', '#90caf9', '#84d9d2', '#da8cff', '#e74a3b'],
                borderWidth: 0
            }]
        },
        options: { cutout: '70%', maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });
    @endisset
});
</script>
</body>
</html>