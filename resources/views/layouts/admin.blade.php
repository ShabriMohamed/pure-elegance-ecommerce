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
    @stack('scripts')
</body>
</html>
