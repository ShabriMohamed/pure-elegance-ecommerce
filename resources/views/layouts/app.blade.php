<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Pure Elegance - Premium fashion for Men & Women. Timeless style, delivered to your door.')">
    <meta property="og:title" content="@yield('title', 'Pure Elegance') | Timeless Fashion">
    <meta property="og:description" content="@yield('meta_description', 'Pure Elegance - Premium fashion for Men & Women.')">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Pure Elegance">
    <meta name="twitter:card" content="summary_large_image">
    <title>@yield('title', 'Pure Elegance') | Timeless Fashion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,300,0,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Top Notification Bar -->
    <div class="top-notification-bar">
        <span class="material-symbols-outlined">local_shipping</span>
        FREE DELIVERY ON ORDERS OVER <strong style="color: var(--color-premium-gold); margin-left: 4px;">LKR 10,000</strong>
    </div>

    <!-- Header -->
    <header class="store-header">
        <div class="container header-inner">
            <button class="icon-btn mobile-only" aria-label="Menu" id="menu-toggle">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <a href="{{ route('home') }}" class="store-logo">
                <div class="store-logo-pe">PE</div>
                <div class="store-logo-text">PURE ELEGANCE</div>
                <span class="material-symbols-outlined store-logo-crown">crown</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('categories') }}?gender=men" class="{{ request()->get('gender') === 'men' ? 'active' : '' }}">Men</a>
                <a href="{{ route('categories') }}?gender=women" class="{{ request()->get('gender') === 'women' ? 'active' : '' }}">Women</a>
                <a href="{{ route('new-arrivals') }}" class="{{ request()->routeIs('new-arrivals') ? 'active' : '' }}">New Arrivals</a>
                <a href="{{ route('sale') }}" class="{{ request()->routeIs('sale') ? 'active' : '' }}">Sale</a>
            </nav>

            <div class="header-actions">
                @auth
                    <a href="{{ route('wishlist.index') }}" class="icon-btn" aria-label="Wishlist">
                        <span class="material-symbols-outlined">favorite_border</span>
                    </a>
                @endauth
                <a href="{{ route('cart.index') }}" class="icon-btn" aria-label="Cart">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    @php
                        try {
                            $cartService = app(\App\Services\CartService::class);
                            $headerCart = $cartService->getCart();
                            $cartCount = $headerCart->items->sum('quantity');
                        } catch (\Exception $e) {
                            $cartCount = 0;
                        }
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <a href="{{ route('account.dashboard') }}" class="icon-btn" aria-label="Account">
                        <span class="material-symbols-outlined">person</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="icon-btn" aria-label="Login">
                        <span class="material-symbols-outlined">person</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Search Bar -->
    <div class="search-bar-container">
        <div class="container">
            <div class="live-search-wrapper" id="live-search-wrapper">
                <form method="GET" action="{{ route('search') }}" class="search-input-wrapper" id="search-form" autocomplete="off">
                    <span class="material-symbols-outlined search-icon">search</span>
                    <input type="text" name="q" id="live-search-input" placeholder="Search for products, brands and more..." value="{{ request('q') }}" autocomplete="off">
                    <button type="button" id="search-clear-btn" class="search-clear-btn" style="display: none;">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </form>

                <!-- Live Search Dropdown -->
                <div class="live-search-dropdown" id="live-search-dropdown">
                    <div class="live-search-loading" id="ls-loading">
                        <div class="ls-spinner"></div>
                        <span>Searching...</span>
                    </div>
                    <div id="ls-results"></div>
                    <div class="live-search-empty" id="ls-empty" style="display: none;">
                        <span class="material-symbols-outlined" style="font-size: 2.5rem; opacity: 0.15;">search_off</span>
                        <div style="margin-top: 0.5rem;">No products found</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div class="mobile-drawer-overlay" id="drawer-overlay"></div>
    <nav class="mobile-drawer" id="mobile-drawer">
        <div class="drawer-header">
            <div>
                <div class="store-logo-pe">PE</div>
                <div class="store-logo-text">PURE ELEGANCE</div>
            </div>
            <button class="icon-btn" id="drawer-close" aria-label="Close menu">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="drawer-section-title">Shop</div>
        <div class="drawer-nav-section">
            <a href="{{ route('home') }}">
                <span class="material-symbols-outlined">home</span> Home
            </a>
            <a href="{{ route('categories') }}?gender=men">
                <span class="material-symbols-outlined">man</span> Men
            </a>
            <a href="{{ route('categories') }}?gender=women">
                <span class="material-symbols-outlined">woman</span> Women
            </a>
            <a href="{{ route('new-arrivals') }}">
                <span class="material-symbols-outlined">local_offer</span> New Arrivals
            </a>
            <a href="{{ route('sale') }}">
                <span class="material-symbols-outlined">sell</span> Sale
            </a>
        </div>
        <div class="drawer-divider"></div>
        <div class="drawer-section-title">Account</div>
        <div class="drawer-nav-section">
            @auth
                <a href="{{ route('account.dashboard') }}">
                    <span class="material-symbols-outlined">person</span> My Account
                </a>
                <a href="{{ route('wishlist.index') }}">
                    <span class="material-symbols-outlined">favorite_border</span> Wishlist
                </a>
                <a href="{{ route('cart.index') }}">
                    <span class="material-symbols-outlined">shopping_bag</span> Shopping Bag
                </a>
                <a href="{{ route('account.orders') }}">
                    <span class="material-symbols-outlined">inventory_2</span> Orders
                </a>
            @else
                <a href="{{ route('login') }}">
                    <span class="material-symbols-outlined">login</span> Login
                </a>
                <a href="{{ route('register') }}">
                    <span class="material-symbols-outlined">person_add</span> Register
                </a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container" style="margin-top: var(--space-md);">
                <div class="alert alert-success">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container" style="margin-top: var(--space-md);">
                <div class="alert alert-error">
                    <span class="material-symbols-outlined">error</span>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="store-logo" style="margin-bottom: var(--space-md);">
                        <div class="store-logo-pe" style="font-size: 2rem;">PE</div>
                        <div class="store-logo-text">PURE ELEGANCE</div>
                    </div>
                    <p class="font-body" style="font-size: 0.85rem; color: var(--color-medium-gray);">
                        Premium fashion for Men & Women. Timeless style, delivered to your door.
                    </p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Shop</h4>
                    <a href="{{ route('categories') }}?gender=men">Men</a>
                    <a href="{{ route('categories') }}?gender=women">Women</a>
                    <a href="{{ route('new-arrivals') }}">New Arrivals</a>
                    <a href="{{ route('sale') }}">Sale</a>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Account</h4>
                    @auth
                        <a href="{{ route('account.dashboard') }}">My Account</a>
                        <a href="{{ route('account.orders') }}">Orders</a>
                        <a href="{{ route('wishlist.index') }}">Wishlist</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                    <a href="{{ route('cart.index') }}">Cart</a>
                </div>
                <div class="footer-col">
                    <h4 class="footer-heading">Contact</h4>
                    <p style="font-size: 0.85rem; color: var(--color-medium-gray);">info@pureelegance.lk</p>
                    <p style="font-size: 0.85rem; color: var(--color-medium-gray);">+94 77 123 4567</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Pure Elegance. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bottom Navigation Bar (Mobile Only) -->
    <nav class="mobile-bottom-nav mobile-only">
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="material-symbols-outlined">home</span>
            <span>Home</span>
        </a>
        <a href="{{ route('categories') }}" class="nav-item {{ request()->routeIs('categories*') || request()->routeIs('category.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">grid_view</span>
            <span>Categories</span>
        </a>
        <a href="{{ route('new-arrivals') }}" class="nav-item {{ request()->routeIs('new-arrivals') ? 'active' : '' }}">
            <span class="material-symbols-outlined">local_offer</span>
            <span>New</span>
        </a>
        <a href="{{ auth()->check() ? route('wishlist.index') : route('login') }}" class="nav-item {{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">{{ request()->routeIs('wishlist.*') ? 'favorite' : 'favorite_border' }}</span>
            <span>Wishlist</span>
        </a>
        <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="nav-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person</span>
            <span>Account</span>
        </a>
    </nav>


    <script>
    (function() {
        const input     = document.getElementById('live-search-input');
        const dropdown  = document.getElementById('live-search-dropdown');
        const wrapper   = document.getElementById('live-search-wrapper');
        const results   = document.getElementById('ls-results');
        const loading   = document.getElementById('ls-loading');
        const empty     = document.getElementById('ls-empty');
        const clearBtn  = document.getElementById('search-clear-btn');
        const form      = document.getElementById('search-form');
        const SUGGEST_URL = '{{ route("search.suggestions") }}';
        let timer       = null;
        let focusIdx    = -1;

        function show() { dropdown.classList.add('visible'); }
        function hide()  { dropdown.classList.remove('visible'); focusIdx = -1; }

        input.addEventListener('input', function() {
            const q = this.value.trim();
            clearBtn.style.display = q.length > 0 ? 'flex' : 'none';
            clearTimeout(timer);
            focusIdx = -1;

            if (q.length < 2) { hide(); return; }

            results.innerHTML = '';
            empty.style.display = 'none';
            loading.style.display = 'flex';
            show();

            timer = setTimeout(() => {
                fetch(SUGGEST_URL + '?q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => { loading.style.display = 'none'; render(data); })
                .catch(() => { loading.style.display = 'none'; empty.style.display = 'block'; });
            }, 220);
        });

        clearBtn.addEventListener('click', function() {
            input.value = ''; clearBtn.style.display = 'none'; hide(); input.focus();
        });

        function render(data) {
            results.innerHTML = '';
            let hasResults = false;

            // Categories
            if (data.categories && data.categories.length) {
                hasResults = true;
                const sec = el('div', 'ls-section');
                sec.appendChild(sectionHeader('category', 'Categories'));
                data.categories.forEach(c => {
                    const a = el('a', 'ls-cat-item');
                    a.href = c.url;
                    a.innerHTML = `<span class="material-symbols-outlined" style="font-size: 1.1rem; color: var(--color-premium-gold);">arrow_forward</span> ${hl(c.name, data.query)}`;
                    sec.appendChild(a);
                });
                results.appendChild(sec);
            }

            // Products
            if (data.products && data.products.length) {
                hasResults = true;
                const sec = el('div', 'ls-section');
                sec.appendChild(sectionHeader('inventory_2', 'Products'));
                data.products.forEach(p => {
                    const a = el('a', 'ls-product-item');
                    a.href = p.url;
                    let priceHtml = `<span class="ls-price">LKR ${p.price}</span>`;
                    if (p.sale_price) {
                        priceHtml = `<span class="ls-price ls-price--sale">LKR ${p.sale_price}</span><span class="ls-price ls-price--orig">LKR ${p.price}</span>`;
                    }
                    a.innerHTML = `
                        <div class="ls-product-img"><img src="${p.image}" alt="" loading="lazy"></div>
                        <div class="ls-product-info">
                            <div class="ls-product-name">${hl(p.name, data.query)}</div>
                            <div class="ls-product-meta">${p.category || ''}${p.brand ? ' · ' + hl(p.brand, data.query) : ''}</div>
                            <div class="ls-product-prices">${priceHtml}</div>
                        </div>
                    `;
                    sec.appendChild(a);
                });
                results.appendChild(sec);

                // View all link
                const viewAll = el('a', 'ls-view-all');
                viewAll.href = '{{ route("search") }}?q=' + encodeURIComponent(input.value.trim());
                viewAll.innerHTML = `View all results <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_forward</span>`;
                results.appendChild(viewAll);
            }

            empty.style.display = hasResults ? 'none' : 'block';
        }

        function el(tag, cls) { const e = document.createElement(tag); if (cls) e.className = cls; return e; }
        function sectionHeader(icon, text) {
            const h = el('div', 'ls-section-header');
            h.innerHTML = `<span class="material-symbols-outlined" style="font-size: 0.9rem;">${icon}</span>${text}`;
            return h;
        }
        function hl(text, q) {
            if (!q || !text) return text || '';
            const esc = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return text.replace(new RegExp(`(${esc})`, 'gi'), '<mark>$1</mark>');
        }

        // Keyboard nav
        input.addEventListener('keydown', function(e) {
            const items = results.querySelectorAll('.ls-product-item, .ls-cat-item');
            if (!items.length || !dropdown.classList.contains('visible')) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); focusIdx = Math.min(focusIdx + 1, items.length - 1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); focusIdx = Math.max(focusIdx - 1, 0); }
            else if (e.key === 'Enter' && focusIdx >= 0) { e.preventDefault(); items[focusIdx].click(); return; }
            else return;
            items.forEach((el, i) => el.classList.toggle('focused', i === focusIdx));
            items[focusIdx]?.scrollIntoView({ block: 'nearest' });
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target)) hide();
        });

        // Show on focus if there's content
        input.addEventListener('focus', function() {
            if (results.innerHTML.trim()) show();
        });
    })();
    </script>
</body>
</html>