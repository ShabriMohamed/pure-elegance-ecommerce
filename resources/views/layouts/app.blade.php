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
                <button class="icon-btn" aria-label="Search" id="sf-search-trigger-icon">
                    <span class="material-symbols-outlined">search</span>
                </button>
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

    <!-- Search Trigger Bar -->
    <div class="search-bar-container">
        <div class="container">
            <button class="sf-search-trigger" id="sf-search-trigger">
                <span class="material-symbols-outlined" style="font-size: 1.15rem; color: var(--color-muted-text);">search</span>
                <span class="sf-search-trigger-text">Search for products, brands and more...</span>
                <kbd class="sf-search-kbd">Ctrl K</kbd>
            </button>
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

    <!-- Search Overlay Modal -->
    <div id="sf-search-overlay" class="sf-search-overlay">
        <div class="sf-search-modal">
            <div class="sf-search-header">
                <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.3rem;">search</span>
                <input type="text" id="sf-search-input" class="sf-search-input" placeholder="Search products, brands, categories..." autocomplete="off" spellcheck="false">
                <kbd class="sf-esc-badge" onclick="sfCloseSearch()">ESC</kbd>
            </div>
            <div id="sf-search-results" class="sf-search-results">
                <div class="sf-search-empty" id="sf-search-empty">
                    <span class="material-symbols-outlined" style="font-size: 3.5rem; opacity: 0.12; display: block;">manage_search</span>
                    <div style="margin-top: 0.75rem; font-size: 0.95rem; color: var(--color-muted-text);">Search our entire collection</div>
                    <div style="margin-top: 0.4rem; font-size: 0.78rem; color: rgba(0,0,0,0.2);">Products · Brands · Categories</div>
                </div>
                <div id="sf-search-loading" class="sf-search-loading" style="display: none;">
                    <div class="sf-spinner"></div>
                    <span>Searching...</span>
                </div>
                <div id="sf-search-list"></div>
                <div id="sf-search-no-results" style="display: none; text-align: center; padding: 2.5rem 1rem;">
                    <span class="material-symbols-outlined" style="font-size: 2.5rem; opacity: 0.12; display: block;">search_off</span>
                    <div style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--color-muted-text);">No products found</div>
                    <div style="margin-top: 0.25rem; font-size: 0.78rem; color: rgba(0,0,0,0.2);">Try a different search term</div>
                </div>
            </div>
            <div class="sf-search-footer">
                <div style="display: flex; gap: 1.25rem; align-items: center; font-size: 0.72rem; color: rgba(0,0,0,0.25);">
                    <span><kbd>↑</kbd><kbd>↓</kbd> Navigate</span>
                    <span><kbd>↵</kbd> Open</span>
                    <span><kbd>Esc</kbd> Close</span>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const overlay    = document.getElementById('sf-search-overlay');
        const input      = document.getElementById('sf-search-input');
        const resultsList = document.getElementById('sf-search-list');
        const emptyState = document.getElementById('sf-search-empty');
        const loadingEl  = document.getElementById('sf-search-loading');
        const noResults  = document.getElementById('sf-search-no-results');
        const SUGGEST_URL = '{{ route("search.suggestions") }}';
        let timer = null, focusIdx = -1;

        // Open / Close
        window.sfOpenSearch = function() {
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
            setTimeout(() => input.focus(), 80);
        };
        window.sfCloseSearch = function() {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
            input.value = '';
            resultsList.innerHTML = '';
            emptyState.style.display = 'block';
            noResults.style.display = 'none';
            loadingEl.style.display = 'none';
            focusIdx = -1;
        };

        // Triggers
        document.getElementById('sf-search-trigger').addEventListener('click', sfOpenSearch);
        document.getElementById('sf-search-trigger-icon').addEventListener('click', sfOpenSearch);
        overlay.addEventListener('click', function(e) { if (e.target === overlay) sfCloseSearch(); });

        // Ctrl+K / Cmd+K
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); sfOpenSearch(); }
            if (e.key === 'Escape' && overlay.classList.contains('open')) sfCloseSearch();
        });

        // Debounced search
        input.addEventListener('input', function() {
            const q = this.value.trim();
            clearTimeout(timer);
            focusIdx = -1;

            if (q.length < 2) {
                resultsList.innerHTML = '';
                noResults.style.display = 'none';
                loadingEl.style.display = 'none';
                emptyState.style.display = 'block';
                return;
            }

            emptyState.style.display = 'none';
            loadingEl.style.display = 'flex';
            noResults.style.display = 'none';

            timer = setTimeout(() => {
                fetch(SUGGEST_URL + '?q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => { loadingEl.style.display = 'none'; render(data); })
                .catch(() => { loadingEl.style.display = 'none'; noResults.style.display = 'block'; });
            }, 220);
        });

        function render(data) {
            resultsList.innerHTML = '';
            let hasResults = false;

            // Categories
            if (data.categories && data.categories.length) {
                hasResults = true;
                const sec = document.createElement('div'); sec.className = 'sf-result-group';
                sec.innerHTML = `<div class="sf-group-header"><span class="material-symbols-outlined" style="font-size: 0.95rem;">category</span>Categories</div>`;
                data.categories.forEach(c => {
                    const a = document.createElement('a'); a.className = 'sf-result-item sf-result-cat';
                    a.href = c.url;
                    a.innerHTML = `<div class="sf-result-avatar"><span class="material-symbols-outlined" style="font-size: 1rem;">folder</span></div><div class="sf-result-info"><div class="sf-result-title">${hl(c.name, data.query)}</div></div><span class="material-symbols-outlined sf-result-arrow">arrow_forward</span>`;
                    sec.appendChild(a);
                });
                resultsList.appendChild(sec);
            }

            // Products
            if (data.products && data.products.length) {
                hasResults = true;
                const sec = document.createElement('div'); sec.className = 'sf-result-group';
                sec.innerHTML = `<div class="sf-group-header"><span class="material-symbols-outlined" style="font-size: 0.95rem;">inventory_2</span>Products</div>`;
                data.products.forEach(p => {
                    const a = document.createElement('a'); a.className = 'sf-result-item';
                    a.href = p.url;
                    let priceHtml = `<span class="sf-result-price">LKR ${p.price}</span>`;
                    if (p.sale_price) {
                        priceHtml = `<span class="sf-result-price sf-result-price--sale">LKR ${p.sale_price}</span><span class="sf-result-price sf-result-price--orig">LKR ${p.price}</span>`;
                    }
                    a.innerHTML = `
                        <div class="sf-result-thumb"><img src="${p.image}" alt="" loading="lazy"></div>
                        <div class="sf-result-info">
                            <div class="sf-result-title">${hl(p.name, data.query)}</div>
                            <div class="sf-result-sub">${p.category || ''}${p.brand ? ' · ' + hl(p.brand, data.query) : ''}</div>
                            <div class="sf-result-prices">${priceHtml}</div>
                        </div>
                        <span class="material-symbols-outlined sf-result-arrow">arrow_forward</span>
                    `;
                    sec.appendChild(a);
                });
                resultsList.appendChild(sec);

                // View all
                const va = document.createElement('a'); va.className = 'sf-view-all';
                va.href = '{{ route("search") }}?q=' + encodeURIComponent(input.value.trim());
                va.innerHTML = `View all results <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_forward</span>`;
                resultsList.appendChild(va);
            }

            noResults.style.display = hasResults ? 'none' : 'block';
        }

        function hl(text, q) {
            if (!q || !text) return text || '';
            const esc = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return text.replace(new RegExp(`(${esc})`, 'gi'), '<mark>$1</mark>');
        }

        // Keyboard nav
        input.addEventListener('keydown', function(e) {
            const items = resultsList.querySelectorAll('.sf-result-item');
            if (!items.length) return;
            if (e.key === 'ArrowDown') { e.preventDefault(); focusIdx = Math.min(focusIdx + 1, items.length - 1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); focusIdx = Math.max(focusIdx - 1, 0); }
            else if (e.key === 'Enter' && focusIdx >= 0) { e.preventDefault(); items[focusIdx].click(); return; }
            else if (e.key === 'Enter') { e.preventDefault(); window.location.href = '{{ route("search") }}?q=' + encodeURIComponent(input.value.trim()); return; }
            else return;
            items.forEach((el, i) => el.classList.toggle('focused', i === focusIdx));
            items[focusIdx]?.scrollIntoView({ block: 'nearest' });
        });
    })();
    </script>
</body>
</html>