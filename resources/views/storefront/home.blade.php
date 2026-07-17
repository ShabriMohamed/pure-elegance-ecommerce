@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- ============================================
    HERO BANNER SECTION — Cinematic Entrance
============================================= --}}
<section class="hero-section" id="hero">
    <div class="hero-bg">
        <img src="{{ asset('images/hero-banner.jpg') }}" alt="Pure Elegance Fashion" class="hero-bg-img">
        <div class="hero-overlay"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-label">STEP INTO STYLE</div>
            <h1 class="hero-heading">TIMELESS FASHION.<br>PURE ELEGANCE.</h1>
            <div class="hero-divider"></div>
            <p class="hero-desc">Premium fashion for Men &amp; Women.<br>Elevate your everyday style.</p>
            <a href="{{ route('categories') }}" class="btn btn-primary hero-btn">
                SHOP NOW <span class="material-symbols-outlined" style="font-size: 1rem;">chevron_right</span>
            </a>
        </div>
    </div>
    <a href="#categories-section" class="hero-scroll-indicator" aria-label="Scroll down">
        <span class="hero-scroll-text">SCROLL</span>
        <span class="material-symbols-outlined">expand_more</span>
    </a>
</section>

{{-- ============================================
    CATEGORY TILES — MEN, WOMEN, OUTLET
============================================= --}}
<section style="padding: var(--space-xl) 0;" id="categories-section">
    <div class="container">
        <div class="category-tiles reveal">
            <a href="{{ route('categories') }}?gender=men" class="cat-tile stagger-item">
                <img src="{{ asset('images/category-men.jpg') }}" alt="Men's Collection" class="cat-tile-img">
                <div class="cat-tile-overlay"></div>
                <div class="cat-tile-content">
                    <div class="cat-tile-title">MEN</div>
                    <span class="cat-tile-cta">SHOP NOW</span>
                </div>
            </a>
            <a href="{{ route('categories') }}?gender=women" class="cat-tile stagger-item">
                <img src="{{ asset('images/category-women.jpg') }}" alt="Women's Collection" class="cat-tile-img">
                <div class="cat-tile-overlay"></div>
                <div class="cat-tile-content">
                    <div class="cat-tile-title">WOMEN</div>
                    <span class="cat-tile-cta">SHOP NOW</span>
                </div>
            </a>
            <a href="{{ route('sale') }}" class="cat-tile stagger-item">
                <img src="{{ asset('images/category-outlet.jpg') }}" alt="Outlet Sale" class="cat-tile-img">
                <div class="cat-tile-overlay"></div>
                <div class="cat-tile-content">
                    <div class="cat-tile-title">OUTLET</div>
                    <span class="cat-tile-cta">UP TO 60% OFF</span>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- ============================================
    VALUE PROPOSITIONS BAR
============================================= --}}
<section style="padding: 0 0 var(--space-xl);">
    <div class="container">
        <div class="value-props reveal">
            <div class="value-prop">
                <span class="material-symbols-outlined value-prop-icon">local_shipping</span>
                <div class="value-prop-title">CASH ON DELIVERY</div>
                <div class="value-prop-desc">Islandwide Delivery</div>
            </div>
            <div class="value-prop-divider"></div>
            <div class="value-prop">
                <span class="material-symbols-outlined value-prop-icon">workspace_premium</span>
                <div class="value-prop-title">100% ORIGINAL</div>
                <div class="value-prop-desc">Branded Products</div>
            </div>
            <div class="value-prop-divider"></div>
            <div class="value-prop">
                <span class="material-symbols-outlined value-prop-icon">sync_alt</span>
                <div class="value-prop-title">EASY RETURNS</div>
                <div class="value-prop-desc">7 Days Return Policy</div>
            </div>
            <div class="value-prop-divider"></div>
            <div class="value-prop">
                <span class="material-symbols-outlined value-prop-icon">lock</span>
                <div class="value-prop-title">SECURE PAYMENT</div>
                <div class="value-prop-desc">Safe &amp; Secure Checkout</div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
    SHOP BY CATEGORY — Browsable Category Grid
============================================= --}}
@if(isset($topCategories) && $topCategories->count() > 0)
<section style="padding-bottom: var(--space-2xl);" id="shop-by-category">
    <div class="container">
        <div class="section-header reveal">
            <div>
                <h2 class="section-title">
                    SHOP BY CATEGORY
                    <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.2rem;">grid_view</span>
                </h2>
                <div class="section-title-underline"></div>
            </div>
        </div>

        <div class="shop-categories-grid">
            @php
                $categoryIcons = [
                    'men' => 'man',
                    'women' => 'woman',
                    'sale' => 'sell',
                    'clothing' => 'checkroom',
                    'footwear' => 'steps',
                    'watches' => 'watch',
                    'accessories' => 'diamond',
                    'fragrances' => 'spa',
                    'bags' => 'shopping_bag',
                    'beauty' => 'cosmetics',
                    'tech' => 'headphones',
                    'skin-care' => 'dermatology',
                    'makeup' => 'brush',
                ];
            @endphp
            @foreach($topCategories as $index => $category)
                {{-- Sale is price-driven: its tile goes to the dedicated /sale page --}}
                <a href="{{ $category->slug === 'sale' ? route('sale') : route('category.show', $category->slug) }}"
                   class="shop-cat-card stagger-item"
                   style="transition-delay: {{ $index * 0.08 }}s;">
                    <div class="shop-cat-icon">
                        <span class="material-symbols-outlined">
                            {{ $categoryIcons[strtolower($category->slug)] ?? 'category' }}
                        </span>
                    </div>
                    <div class="shop-cat-name">{{ $category->name }}</div>
                    <div class="shop-cat-count">{{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================
    FEATURED PRODUCTS (from DB is_featured flag)
============================================= --}}
@if($featuredProducts->count() > 0)
<section class="section-bg-beige" style="padding: var(--space-2xl) 0;" id="featured-section">
    <div class="container skeleton-container">
        <div class="section-header reveal">
            <div>
                <h2 class="section-title">
                    FEATURED
                    <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.2rem;">star</span>
                </h2>
                <div class="section-title-underline"></div>
            </div>
            <a href="{{ route('categories') }}" class="section-link">
                VIEW ALL <span class="material-symbols-outlined" style="font-size: 1.1rem;">arrow_forward</span>
            </a>
        </div>

        <div class="skeleton-grid" aria-hidden="true">
            @for($i = 0; $i < 4; $i++)
                @include('storefront.partials.skeleton-product-card')
            @endfor
        </div>

        <div class="product-real-grid home-product-grid">
            @foreach($featuredProducts as $index => $product)
                <div class="home-product-item stagger-item" style="transition-delay: {{ $index * 0.1 }}s;">
                    @include('storefront.partials.product-card', [
                        'product'     => $product,
                        'wishlistIds' => $wishlistIds ?? [],
                    ])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================
    BRAND STORY / ABOUT SECTION
============================================= --}}
<section class="brand-story-section" style="padding: var(--space-3xl) 0;">
    <div class="container">
        <div class="brand-story-grid">
            <div class="brand-story-content reveal">
                <div class="brand-story-label">OUR STORY</div>
                <h2 class="brand-story-heading">Discover Original<br>Branded Fashion</h2>
                <div class="brand-story-divider"></div>
                <p class="brand-story-text">
                    At Pure Elegance, we believe fashion is more than clothing — it's a statement of who you are.
                    We curate original branded pieces from the finest designers, bringing you premium fashion
                    that combines timeless elegance with contemporary style. Every item in our collection is
                    handpicked to ensure authenticity, quality, and the perfect fit for the modern individual.
                </p>
                <a href="{{ route('categories') }}" class="brand-story-btn">
                    EXPLORE COLLECTION
                    <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_forward</span>
                </a>
            </div>
            <div class="brand-story-img-wrapper reveal reveal-delay-2">
                <img src="{{ asset('images/hero-banner.jpg') }}" alt="Pure Elegance Brand Story">
                <div class="brand-story-img-badge">
                    <div class="brand-story-img-badge-title">PURE ELEGANCE</div>
                    <div class="brand-story-img-badge-text">Premium Fashion Since 2024</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
    NEW ARRIVALS
============================================= --}}
@if($newArrivals->count() > 0)
<section style="padding: var(--space-2xl) 0;" id="new-arrivals-section">
    <div class="container skeleton-container">
        <div class="section-header reveal">
            <div>
                <h2 class="section-title">
                    NEW ARRIVALS
                    <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.2rem;">crown</span>
                </h2>
                <div class="section-title-underline"></div>
            </div>
            <a href="{{ route('new-arrivals') }}" class="section-link">
                VIEW ALL <span class="material-symbols-outlined" style="font-size: 1.1rem;">arrow_forward</span>
            </a>
        </div>

        <div class="skeleton-grid" aria-hidden="true">
            @for($i = 0; $i < 4; $i++)
                @include('storefront.partials.skeleton-product-card')
            @endfor
        </div>

        <div class="product-real-grid home-product-grid">
            @foreach($newArrivals as $index => $product)
                <div class="home-product-item stagger-item" style="transition-delay: {{ $index * 0.1 }}s;">
                    @include('storefront.partials.product-card', [
                        'product'     => $product,
                        'wishlistIds' => $wishlistIds ?? [],
                    ])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================
    PROMO BANNER
============================================= --}}
<section style="padding-bottom: var(--space-2xl);">
    <div class="container">
        <div class="promo-banner reveal">
            <div class="promo-left">
                <div class="promo-icon">
                    <span class="material-symbols-outlined">featured_seasonal_and_gifts</span>
                </div>
                <div>
                    <div class="promo-heading">GET 10% OFF YOUR FIRST ORDER</div>
                    <div class="promo-desc">Join our community and enjoy exclusive offers.</div>
                </div>
            </div>
            <a href="{{ route('register') }}" class="btn btn-outline-gold promo-btn">JOIN NOW</a>
        </div>
    </div>
</section>

{{-- ============================================
    NEWSLETTER SECTION
============================================= --}}
<section class="newsletter-section" style="padding: var(--space-3xl) 0;">
    <div class="container">
        <div class="newsletter-inner reveal">
            <div class="newsletter-label">STAY IN THE LOOP</div>
            <h2 class="newsletter-heading">Get the Latest Drops<br>Straight to Your Inbox</h2>
            <p class="newsletter-desc">Be the first to know about new collections, exclusive deals, and style inspiration.</p>
            <form class="newsletter-form" onsubmit="event.preventDefault(); this.querySelector('button').textContent='SUBSCRIBED ✓'; this.querySelector('input').value='';">
                <input type="email" class="newsletter-input" placeholder="Enter your email address" required>
                <button type="submit" class="newsletter-submit">SUBSCRIBE</button>
            </form>
            <div class="newsletter-trust">
                <span class="material-symbols-outlined">lock</span>
                No spam. Unsubscribe anytime.
            </div>
        </div>
    </div>
</section>

{{-- ============================================
    SCROLL-REVEAL & SKELETON TRANSITION SCRIPT
============================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Skeleton → Real content transition
    document.querySelectorAll('.skeleton-container').forEach(function (el) {
        el.classList.add('content-loaded');
    });

    // IntersectionObserver for scroll-reveal animations
    const revealElements = document.querySelectorAll('.reveal, .stagger-item');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -40px 0px'
        });

        revealElements.forEach(function (el) {
            observer.observe(el);
        });
    } else {
        // Fallback: show everything immediately
        revealElements.forEach(function (el) {
            el.classList.add('revealed');
        });
    }
});
</script>

@endsection
