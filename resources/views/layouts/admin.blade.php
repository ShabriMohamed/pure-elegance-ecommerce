<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | Pure Elegance</title>
    
    <!-- Google Fonts & Material Symbols -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --admin-sidebar-bg: linear-gradient(180deg, #1A1A1D 0%, #0F0F11 100%);
            --admin-sidebar-hover: rgba(255, 255, 255, 0.04);
            --admin-sidebar-active: rgba(212, 175, 55, 0.1);
            --admin-card-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
            --admin-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body {
            background-color: #F8F9FA;
            font-family: 'Poppins', sans-serif;
            color: var(--color-charcoal);
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 280px;
            background: var(--admin-sidebar-bg);
            color: rgba(255, 255, 255, 0.7);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 4px 0 24px rgba(0,0,0,0.04);
        }
        
        .admin-main {
            flex-grow: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #F8F9FA;
        }
        
        .admin-brand {
            padding: 2rem 1.5rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 3px;
            color: var(--color-gold);
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            position: relative;
        }
        
        .admin-brand::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 1px;
            background: var(--color-gold);
        }
        
        .admin-nav {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .admin-nav-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.25rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 12px;
            transition: var(--admin-transition);
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .admin-nav-item:hover {
            color: #FFFFFF;
            background-color: var(--admin-sidebar-hover);
            transform: translateX(4px);
        }
        
        .admin-nav-item.active {
            color: var(--color-gold);
            background-color: var(--admin-sidebar-active);
        }
        
        .admin-nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 4px;
            background: var(--color-gold);
            border-radius: 0 4px 4px 0;
        }
        
        .admin-nav-item .material-symbols-outlined {
            font-size: 1.35rem;
            transition: var(--admin-transition);
        }
        
        .admin-nav-item:hover .material-symbols-outlined, 
        .admin-nav-item.active .material-symbols-outlined {
            transform: scale(1.1);
        }
        
        .admin-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .admin-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            color: var(--color-obsidian);
        }
        
        .admin-content {
            padding: 2rem;
            flex-grow: 1;
        }
        
        .admin-card {
            background: #FFFFFF;
            border: 1px solid rgba(0,0,0,0.03);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--admin-card-shadow);
        }
        
        .btn-block { display: block; width: 100%; text-align: center; }
        
        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .admin-table th {
            text-align: left;
            padding: 1rem 1.25rem;
            border-bottom: 2px solid rgba(0,0,0,0.04);
            color: var(--color-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: rgba(0,0,0,0.01);
        }
        
        .admin-table th:first-child { border-top-left-radius: 8px; }
        .admin-table th:last-child { border-top-right-radius: 8px; }
        
        .admin-table td {
            padding: 1.25rem;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            font-size: 0.9rem;
            vertical-align: middle;
            transition: var(--admin-transition);
        }
        
        .admin-table tr:hover td {
            background-color: rgba(0,0,0,0.01);
        }
        
        .admin-table tr:last-child td {
            border-bottom: none;
        }
        
        .badge-success { background: rgba(46, 125, 50, 0.1); color: #2E7D32; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px; }
        .badge-warning { background: rgba(230, 81, 0, 0.1); color: #E65100; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px; }
        .badge-error { background: rgba(198, 40, 40, 0.1); color: #C62828; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 4px; }
        
        .btn-outline {
            border-radius: 8px;
            transition: var(--admin-transition);
            background: #FFFFFF;
        }
        
        .btn-outline:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .admin-overlay {
            display: none;
        }
        
        @media (max-width: 992px) {
            .admin-sidebar { 
                transform: translateX(-100%); 
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
                box-shadow: none;
            }
            .admin-sidebar.open { 
                transform: translateX(0); 
                box-shadow: 10px 0 30px rgba(0,0,0,0.2);
            }
            .admin-main { margin-left: 0; }
            .mobile-menu-btn { display: flex !important; align-items: center; justify-content: center; border: none; background: transparent; cursor: pointer; color: var(--color-obsidian); padding: 0.5rem; }
            
            .admin-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.4);
                backdrop-filter: blur(4px);
                z-index: 90;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .admin-overlay.open {
                display: block;
                opacity: 1;
            }
        }
        .form-label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--color-charcoal); margin-bottom: 0.4rem; letter-spacing: 0.3px; }
        .form-control { width: 100%; padding: 0.65rem 0.9rem; border: 1.5px solid rgba(0,0,0,0.12); border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 0.875rem; color: var(--color-charcoal); background: white; transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
        .form-control:focus { border-color: var(--color-gold); box-shadow: 0 0 0 3px rgba(212,175,55,0.12); }
        .form-control.is-invalid { border-color: #C62828; }
        .form-error { color: #C62828; font-size: 0.78rem; margin-top: 0.3rem; }
        .form-group { margin-bottom: 1.1rem; }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; }
        .btn-primary { background: var(--color-gold); color: white; border: none; padding: 0.65rem 1.5rem; border-radius: 10px; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-primary:hover { background: #c9a832; box-shadow: 0 4px 12px rgba(212,175,55,0.3); }
        .btn-outline { background: white; color: var(--color-charcoal); border: 1.5px solid rgba(0,0,0,0.12); padding: 0.65rem 1.5rem; border-radius: 10px; font-family: 'Poppins', sans-serif; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; }
        .btn-outline:hover { border-color: rgba(0,0,0,0.25); box-shadow: 0 2px 8px rgba(0,0,0,0.06); }

        /* ─── Global Search ─── */
        .search-trigger-btn {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.45rem 0.9rem; border: 1.5px solid rgba(0,0,0,0.1);
            border-radius: 10px; background: rgba(0,0,0,0.02);
            cursor: pointer; transition: all 0.2s; font-family: 'Poppins', sans-serif;
        }
        .search-trigger-btn:hover { border-color: var(--color-gold); background: rgba(212,175,55,0.04); box-shadow: 0 2px 12px rgba(212,175,55,0.08); }
        .search-trigger-text { font-size: 0.82rem; color: var(--color-muted); }
        .search-kbd { font-size: 0.65rem; color: rgba(0,0,0,0.3); background: rgba(0,0,0,0.05); padding: 0.15rem 0.4rem; border-radius: 4px; font-family: monospace; border: 1px solid rgba(0,0,0,0.08); margin-left: 0.25rem; }

        .search-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.45); backdrop-filter: blur(6px);
            display: flex; align-items: flex-start; justify-content: center;
            padding-top: 12vh; opacity: 0; pointer-events: none;
            transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .search-overlay.open { opacity: 1; pointer-events: auto; }

        .search-modal {
            width: 640px; max-width: 94vw;
            background: #FFFFFF; border-radius: 18px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.04);
            transform: translateY(-16px) scale(0.98);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }
        .search-overlay.open .search-modal { transform: translateY(0) scale(1); }

        .search-header {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 1rem 1.25rem; border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .search-header-icon { color: var(--color-gold); font-size: 1.3rem; }
        .search-input {
            flex: 1; border: none; outline: none; background: transparent;
            font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 500;
            color: var(--color-charcoal); padding: 0.2rem 0;
        }
        .search-input::placeholder { color: rgba(0,0,0,0.25); font-weight: 400; }
        .search-esc-badge { font-size: 0.65rem; background: rgba(0,0,0,0.06); color: rgba(0,0,0,0.35); padding: 0.2rem 0.5rem; border-radius: 5px; cursor: pointer; border: 1px solid rgba(0,0,0,0.08); font-family: monospace; }
        .search-esc-badge:hover { background: rgba(0,0,0,0.1); }

        .search-results {
            max-height: 420px; overflow-y: auto;
            scroll-behavior: smooth;
        }
        .search-results::-webkit-scrollbar { width: 4px; }
        .search-results::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 4px; }

        .search-empty-state { text-align: center; padding: 3rem 1rem; }
        .search-loading { display: flex; align-items: center; justify-content: center; gap: 0.75rem; padding: 2rem; color: var(--color-muted); font-size: 0.85rem; }
        .search-spinner {
            width: 20px; height: 20px; border: 2.5px solid rgba(0,0,0,0.08);
            border-top-color: var(--color-gold); border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .search-group { padding: 0.25rem 0; }
        .search-group-header {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1.25rem; font-size: 0.7rem; font-weight: 700;
            color: var(--color-muted); text-transform: uppercase; letter-spacing: 1.5px;
        }

        .search-result-item {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.65rem 1.25rem; text-decoration: none; color: var(--color-charcoal);
            transition: background 0.12s; cursor: pointer; border-left: 3px solid transparent;
        }
        .search-result-item:hover, .search-result-item.focused {
            background: rgba(212,175,55,0.06); border-left-color: var(--color-gold);
        }
        .search-item-img {
            width: 38px; height: 38px; border-radius: 8px; overflow: hidden;
            flex-shrink: 0; background: #F5F5F5; border: 1px solid rgba(0,0,0,0.06);
        }
        .search-item-img img { width: 100%; height: 100%; object-fit: cover; }
        .search-item-avatar {
            width: 38px; height: 38px; border-radius: 8px; flex-shrink: 0;
            background: linear-gradient(135deg, rgba(212,175,55,0.15), rgba(212,175,55,0.05));
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem; color: var(--color-gold);
        }
        .search-item-info { flex: 1; min-width: 0; }
        .search-item-title { font-weight: 600; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .search-item-title mark { background: rgba(212,175,55,0.25); color: inherit; border-radius: 2px; padding: 0 1px; }
        .search-item-subtitle { font-size: 0.75rem; color: var(--color-muted); margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .search-item-subtitle mark { background: rgba(212,175,55,0.2); color: inherit; border-radius: 2px; padding: 0 1px; }
        .search-item-arrow { font-size: 1rem; color: rgba(0,0,0,0.15); transition: all 0.15s; margin-left: auto; }
        .search-result-item:hover .search-item-arrow, .search-result-item.focused .search-item-arrow { color: var(--color-gold); transform: translateX(2px); }

        .search-footer {
            padding: 0.6rem 1.25rem; border-top: 1px solid rgba(0,0,0,0.06);
            display: flex; justify-content: center;
        }
        .search-footer kbd {
            font-family: monospace; font-size: 0.65rem; background: rgba(0,0,0,0.05);
            padding: 0.1rem 0.35rem; border-radius: 3px; border: 1px solid rgba(0,0,0,0.08);
            margin: 0 2px;
        }

        @media (max-width: 600px) {
            .search-trigger-text, .search-kbd { display: none; }
            .search-trigger-btn { padding: 0.4rem 0.6rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-container">
        
        <div class="admin-overlay" id="admin-overlay"></div>
        
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="admin-brand">PURE ELEGANCE</div>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">dashboard</span> 
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="admin-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">shopping_cart</span> 
                    <span>Orders</span>
                    @php $pendingCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span style="margin-left: auto; background: #C62828; color: white; font-size: 0.65rem; padding: 0.1rem 0.4rem; border-radius: 10px; font-weight: 600;">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">inventory_2</span> 
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">category</span> 
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.banners.index') }}" class="admin-nav-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">image</span> 
                    <span>Banners</span>
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="admin-nav-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">sell</span> 
                    <span>Promotions</span>
                </a>
                <a href="{{ route('admin.customers.index') }}" class="admin-nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">group</span> 
                    <span>Customers</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span> 
                    <span>Settings</span>
                </a>
            </nav>
            <div style="padding: 1.5rem; text-align: center; border-top: 1px solid rgba(255,255,255,0.05);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="color: rgba(255,255,255,0.7); border-color: rgba(255,255,255,0.2); width: 100%; background: transparent; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.75rem;">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">logout</span> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button id="mobile-menu-btn" class="mobile-menu-btn" style="display: none;">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1>@yield('title')</h1>
                </div>
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <button id="search-trigger" class="search-trigger-btn" title="Search (Ctrl+K)">
                        <span class="material-symbols-outlined" style="font-size: 1.15rem;">search</span>
                        <span class="search-trigger-text">Search...</span>
                        <kbd class="search-kbd">Ctrl K</kbd>
                    </button>
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span class="material-symbols-outlined" style="font-size: 1.1rem;">visibility</span> View Store
                    </a>
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--color-charcoal); font-weight: 500;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-gold); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span style="display: none; @media(min-width: 768px) { display: inline; }">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div style="padding: 0.5rem 2rem; border-bottom: 1px solid rgba(0,0,0,0.04); background: rgba(255,255,255,0.5);">@yield('breadcrumb')</div>
            <div class="admin-content">
                @if(session('success'))
                    <div class="flash-message flash-success" style="background: rgba(46, 125, 50, 0.08); color: #2E7D32; border: 1px solid rgba(46, 125, 50, 0.2); padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 12px rgba(46, 125, 50, 0.05); transition: opacity 0.5s, transform 0.5s;">
                        <span class="material-symbols-outlined">check_circle</span>
                        <div style="font-weight: 500; font-size: 0.9rem; flex: 1;">{{ session('success') }}</div>
                        <button onclick="this.closest('.flash-message').remove()" style="background: none; border: none; cursor: pointer; color: #2E7D32; padding: 0.1rem;">
                            <span class="material-symbols-outlined" style="font-size: 1rem;">close</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flash-message flash-error" style="background: rgba(198, 40, 40, 0.08); color: #C62828; border: 1px solid rgba(198, 40, 40, 0.2); padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 12px rgba(198, 40, 40, 0.05); transition: opacity 0.5s, transform 0.5s;">
                        <span class="material-symbols-outlined">error</span>
                        <div style="font-weight: 500; font-size: 0.9rem; flex: 1;">{{ session('error') }}</div>
                        <button onclick="this.closest('.flash-message').remove()" style="background: none; border: none; cursor: pointer; color: #C62828; padding: 0.1rem;">
                            <span class="material-symbols-outlined" style="font-size: 1rem;">close</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('admin-overlay');
        const menuBtn = document.getElementById('mobile-menu-btn');
        
        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }

        menuBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Auto-dismiss flash messages after 5 seconds
        document.querySelectorAll('.flash-message').forEach(function(el) {
            setTimeout(function() {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(function() { el.remove(); }, 500);
            }, 5000);
        });
    </script>

    <!-- Global Search Overlay -->
    <div id="search-overlay" class="search-overlay">
        <div class="search-modal">
            <div class="search-header">
                <span class="material-symbols-outlined search-header-icon">search</span>
                <input type="text" id="search-input" class="search-input" placeholder="Search products, orders, customers..." autocomplete="off" spellcheck="false">
                <kbd class="search-esc-badge" onclick="closeSearch()">ESC</kbd>
            </div>
            <div id="search-results" class="search-results">
                <div class="search-empty-state" id="search-empty">
                    <span class="material-symbols-outlined" style="font-size: 3rem; opacity: 0.2; display: block;">manage_search</span>
                    <div style="margin-top: 0.75rem; font-size: 0.9rem; color: var(--color-muted);">Start typing to search across your store</div>
                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: rgba(0,0,0,0.25);">Products · Orders · Customers · Categories</div>
                </div>
                <div id="search-loading" class="search-loading" style="display: none;">
                    <div class="search-spinner"></div>
                    <span>Searching...</span>
                </div>
                <div id="search-results-list"></div>
                <div id="search-no-results" style="display: none; text-align: center; padding: 2.5rem 1rem;">
                    <span class="material-symbols-outlined" style="font-size: 2.5rem; opacity: 0.2; display: block;">search_off</span>
                    <div style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--color-muted);">No results found</div>
                </div>
            </div>
            <div class="search-footer">
                <div style="display: flex; gap: 1.25rem; align-items: center; font-size: 0.72rem; color: rgba(0,0,0,0.3);">
                    <span><kbd>↑</kbd><kbd>↓</kbd> Navigate</span>
                    <span><kbd>↵</kbd> Open</span>
                    <span><kbd>Esc</kbd> Close</span>
                </div>
            </div>
        </div>
    </div>

    <script>
    // ─── Global Search ───
    const searchOverlay   = document.getElementById('search-overlay');
    const searchInput     = document.getElementById('search-input');
    const searchResultsList = document.getElementById('search-results-list');
    const searchEmpty     = document.getElementById('search-empty');
    const searchLoading   = document.getElementById('search-loading');
    const searchNoResults = document.getElementById('search-no-results');
    const searchTrigger   = document.getElementById('search-trigger');
    const SEARCH_URL      = '{{ route("admin.search") }}';
    let debounceTimer     = null;
    let currentFocus      = -1;

    function openSearch() {
        searchOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput.focus(), 80);
    }

    function closeSearch() {
        searchOverlay.classList.remove('open');
        document.body.style.overflow = '';
        searchInput.value = '';
        searchResultsList.innerHTML = '';
        searchEmpty.style.display = 'block';
        searchNoResults.style.display = 'none';
        searchLoading.style.display = 'none';
        currentFocus = -1;
    }

    searchTrigger.addEventListener('click', openSearch);
    searchOverlay.addEventListener('click', function(e) { if (e.target === searchOverlay) closeSearch(); });

    // Ctrl+K / Cmd+K to open
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); openSearch(); }
        if (e.key === 'Escape' && searchOverlay.classList.contains('open')) closeSearch();
    });

    // Debounced live search
    searchInput.addEventListener('input', function() {
        const q = this.value.trim();
        clearTimeout(debounceTimer);
        currentFocus = -1;

        if (q.length < 2) {
            searchResultsList.innerHTML = '';
            searchNoResults.style.display = 'none';
            searchLoading.style.display = 'none';
            searchEmpty.style.display = 'block';
            return;
        }

        searchEmpty.style.display = 'none';
        searchLoading.style.display = 'flex';
        searchNoResults.style.display = 'none';

        debounceTimer = setTimeout(() => {
            fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                searchLoading.style.display = 'none';
                renderResults(data);
            })
            .catch(() => {
                searchLoading.style.display = 'none';
                searchNoResults.style.display = 'block';
            });
        }, 250);
    });

    function renderResults(data) {
        searchResultsList.innerHTML = '';

        if (!data.results || data.results.length === 0) {
            searchNoResults.style.display = 'block';
            return;
        }

        searchNoResults.style.display = 'none';

        data.results.forEach(group => {
            const section = document.createElement('div');
            section.className = 'search-group';

            const header = document.createElement('div');
            header.className = 'search-group-header';
            header.innerHTML = `<span class="material-symbols-outlined" style="font-size: 1rem;">${group.icon}</span>${group.type}`;
            section.appendChild(header);

            group.items.forEach(item => {
                const a = document.createElement('a');
                a.href = item.url;
                a.className = 'search-result-item';

                let imgHtml = '';
                if (item.image) {
                    imgHtml = `<div class="search-item-img"><img src="${item.image}" alt=""></div>`;
                } else {
                    const letter = item.title.charAt(0).toUpperCase();
                    imgHtml = `<div class="search-item-avatar">${letter}</div>`;
                }

                let badgeHtml = '';
                if (item.badge) {
                    badgeHtml = `<span class="badge-${item.badgeType}" style="font-size: 0.65rem; padding: 0.2rem 0.5rem;">${item.badge}</span>`;
                }

                a.innerHTML = `
                    ${imgHtml}
                    <div class="search-item-info">
                        <div class="search-item-title">${highlight(item.title, data.query)}</div>
                        <div class="search-item-subtitle">${highlight(item.subtitle, data.query)}</div>
                    </div>
                    ${badgeHtml}
                    <span class="material-symbols-outlined search-item-arrow">arrow_forward</span>
                `;
                section.appendChild(a);
            });

            searchResultsList.appendChild(section);
        });
    }

    function highlight(text, query) {
        if (!query || !text) return text || '';
        const esc = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        return text.replace(new RegExp(`(${esc})`, 'gi'), '<mark>$1</mark>');
    }

    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResultsList.querySelectorAll('.search-result-item');
        if (!items.length) return;

        if (e.key === 'ArrowDown') { e.preventDefault(); currentFocus = Math.min(currentFocus + 1, items.length - 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); currentFocus = Math.max(currentFocus - 1, 0); }
        else if (e.key === 'Enter' && currentFocus >= 0) { e.preventDefault(); items[currentFocus].click(); return; }
        else return;

        items.forEach((el, i) => el.classList.toggle('focused', i === currentFocus));
        items[currentFocus]?.scrollIntoView({ block: 'nearest' });
    });
    </script>
    @stack('scripts')
</body>
</html>
