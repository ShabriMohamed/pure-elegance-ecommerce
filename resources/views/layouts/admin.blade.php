<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | Pure Elegance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: var(--color-cream);
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            background-color: var(--color-obsidian);
            color: var(--color-ivory);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        .admin-main {
            flex-grow: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
        }
        .admin-brand {
            padding: var(--space-xl) var(--space-lg);
            font-family: var(--font-serif);
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--color-gold);
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .admin-nav {
            padding: var(--space-lg) 0;
            flex-grow: 1;
        }
        .admin-nav-item {
            display: flex;
            align-items: center;
            padding: var(--space-md) var(--space-xl);
            color: var(--color-muted);
            font-size: 0.9375rem;
            transition: var(--transition-base);
            gap: var(--space-md);
        }
        .admin-nav-item:hover, .admin-nav-item.active {
            color: var(--color-gold);
            background-color: rgba(255,255,255,0.02);
            border-left: 3px solid var(--color-gold);
        }
        .admin-nav-item .material-symbols-outlined {
            font-size: 1.25rem;
        }
        .admin-header {
            background-color: var(--color-ivory);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 var(--space-xl);
            border-bottom: 1px solid var(--color-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .admin-content {
            padding: var(--space-xl);
            flex-grow: 1;
        }
        .admin-card {
            background: var(--color-ivory);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: var(--space-lg);
            box-shadow: var(--shadow-sm);
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th {
            text-align: left;
            padding: var(--space-md);
            border-bottom: 1px solid var(--color-border);
            color: var(--color-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .admin-table td {
            padding: var(--space-md);
            border-bottom: 1px solid var(--color-border);
            font-size: 0.9375rem;
        }
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        .badge-success { background: #E8F5E9; color: var(--color-success); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500; }
        .badge-warning { background: #FFF3E0; color: #E65100; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500; }
        .badge-error { background: #FFEBEE; color: var(--color-error); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500; }
        
        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .mobile-menu-btn { display: block !important; }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="admin-brand">PURE ELEGANCE</div>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
                <a href="{{ route('admin.orders.index') }}" class="admin-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">shopping_cart</span> Orders
                </a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">inventory_2</span> Products
                </a>
                <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">category</span> Categories
                </a>
                <a href="{{ route('admin.customers.index') }}" class="admin-nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">group</span> Customers
                </a>
                <a href="{{ route('admin.settings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span> Settings
                </a>
            </nav>
            <div style="padding: var(--space-xl); text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="color: var(--color-muted); border-color: var(--color-muted); width: 100%;">
                        <span class="material-symbols-outlined">logout</span> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    <button id="mobile-menu-btn" class="icon-btn mobile-menu-btn" style="display: none;">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1 style="font-size: 1.25rem; margin: 0;">@yield('title')</h1>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem;">View Store</a>
                    <div style="display: flex; align-items: center; gap: var(--space-sm); color: var(--color-charcoal); font-weight: 500;">
                        <span class="material-symbols-outlined" style="color: var(--color-gold);">account_circle</span>
                        {{ Auth::user()->name }}
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div style="background: #E8F5E9; color: var(--color-success); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 0.5rem;">
                        <span class="material-symbols-outlined">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div style="background: #FFEBEE; color: var(--color-error); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 0.5rem;">
                        <span class="material-symbols-outlined">error</span>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>
</body>
</html>
