@extends('layouts.app')

@section('title', 'Home')

@section('content')

{{-- ============================================
    HERO BANNER SECTION
    Matches UI: Full-width hero with gradient overlay,
    "STEP INTO STYLE" label, serif heading, sub text, CTA
============================================= --}}
<section class="hero-section">
    <div class="hero-bg">
        <img src="{{ asset('images/hero-banner.jpg') }}" alt="Pure Elegance Fashion" class="hero-bg-img">
        <div class="hero-overlay"></div>
    </div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-label">STEP INTO STYLE</div>
            <h1 class="hero-heading">TIMELESS FASHION.<br>PURE ELEGANCE.</h1>
            <div class="hero-divider"></div>
            <p class="hero-desc">Premium fashion for Men & Women.<br>Elevate your everyday style.</p>
            <a href="{{ route('categories') }}" class="btn btn-primary hero-btn">
                SHOP NOW <span class="material-symbols-outlined" style="font-size: 1rem;">chevron_right</span>
            </a>
        </div>
    </div>
    <div class="hero-dots">
        <span class="hero-dot active"></span>
        <span class="hero-dot"></span>
        <span class="hero-dot"></span>
    </div>
</section>

{{-- ============================================
    CATEGORY TILES
    Matches UI: 3 tiles - MEN, WOMEN, OUTLET
============================================= --}}
<section style="padding: var(--space-lg) 0;">
    <div class="container">
        <div class="category-tiles">
            <a href="{{ route('categories') }}?gender=men" class="cat-tile">
                <img src="{{ asset('images/category-men.jpg') }}" alt="Men's Collection" class="cat-tile-img">
                <div class="cat-tile-overlay"></div>
                <div class="cat-tile-content">
                    <div class="cat-tile-title">MEN</div>
                    <span class="cat-tile-cta">SHOP NOW</span>
                </div>
            </a>
            <a href="{{ route('categories') }}?gender=women" class="cat-tile">
                <img src="{{ asset('images/category-women.jpg') }}" alt="Women's Collection" class="cat-tile-img">
                <div class="cat-tile-overlay"></div>
                <div class="cat-tile-content">
                    <div class="cat-tile-title">WOMEN</div>
                    <span class="cat-tile-cta">SHOP NOW</span>
                </div>
            </a>
            <a href="{{ route('sale') }}" class="cat-tile">
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
    Matches UI: 4 props with icons, dividers
============================================= --}}
<section style="padding: 0 0 var(--space-xl);">
    <div class="container">
        <div class="value-props">
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
                <div class="value-prop-desc">Safe & Secure Checkout</div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================
    NEW ARRIVALS
    Matches UI: Section heading with gold underline,
    crown icon, "VIEW ALL ->" link, product grid
============================================= --}}
@if($newArrivals->count() > 0)
<section style="padding-bottom: var(--space-2xl);" id="new-arrivals-section">
    <div class="container skeleton-container">
        <div class="section-header">
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

        {{-- Skeleton Loading --}}
        <div class="skeleton-grid" id="arrivals-skeleton" aria-hidden="true">
            @for($i = 0; $i < 4; $i++)
                @include('storefront.partials.skeleton-product-card')
            @endfor
        </div>

        {{-- Real Products --}}
        <div class="new-arrivals-grid product-real-grid" id="arrivals-grid">
            @foreach($newArrivals as $product)
                <div class="new-arrivals-item">
                    @include('storefront.partials.product-card', [
                        'product'     => $product,
                        'wishlistIds' => $wishlistIds ?? [],
                    ])
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var container = document.querySelector('#new-arrivals-section .skeleton-container');
        if (container) {
            container.classList.add('content-loaded');
        }
    });
</script>
@endif

{{-- ============================================
    PROMO BANNER
    Matches UI: Black banner, gold icon, gold heading,
    white subtitle, outlined "JOIN NOW" CTA
============================================= --}}
<section style="padding-bottom: var(--space-2xl);">
    <div class="container">
        <div class="promo-banner">
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
    HOMEPAGE STYLES (scoped to homepage components)
============================================= --}}
<style>
/* HERO */
.hero-section {
    position: relative;
    width: 100%;
    height: 420px;
    overflow: hidden;
    display: flex;
    align-items: center;
    background: var(--color-rich-black);
}
@media (min-width: 769px) {
    .hero-section { height: 560px; }
}
.hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
}
.hero-bg-img {
    width: 100%; height: 100%; object-fit: cover;
}
.hero-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(90deg, rgba(11,11,11,0.88) 0%, rgba(11,11,11,0.55) 45%, rgba(11,11,11,0.1) 100%);
}
.hero-content {
    position: relative;
    z-index: 10;
    max-width: 520px;
}
.hero-label {
    font-family: var(--font-sans);
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-premium-gold);
    letter-spacing: 2.5px;
    text-transform: uppercase;
    margin-bottom: var(--space-sm);
}
.hero-heading {
    font-family: var(--font-serif);
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 700;
    color: var(--color-pure-white);
    line-height: 1.1;
    margin-bottom: var(--space-md);
}
.hero-divider {
    width: 50px;
    height: 3px;
    background: var(--color-premium-gold);
    margin-bottom: var(--space-md);
}
.hero-desc {
    font-family: var(--font-sans);
    font-size: 0.95rem;
    font-weight: 400;
    color: rgba(255,255,255,0.75);
    line-height: 1.7;
    margin-bottom: var(--space-lg);
}
.hero-btn {
    padding: 14px 32px;
    font-size: 0.85rem;
}
.hero-dots {
    position: absolute;
    bottom: 24px;
    width: 100%;
    display: flex;
    justify-content: center;
    gap: 10px;
    z-index: 10;
}
.hero-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    background: rgba(255,255,255,0.4);
    cursor: pointer;
    transition: background var(--transition-base);
}
.hero-dot.active {
    background: var(--color-premium-gold);
}

/* CATEGORY TILES */
.category-tiles {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-md);
}
.cat-tile {
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    height: 200px;
    display: block;
    background: var(--color-soft-gray);
}
@media (min-width: 769px) {
    .cat-tile { height: 300px; }
}
.cat-tile-img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform var(--transition-slow);
}
.cat-tile:hover .cat-tile-img {
    transform: scale(1.06);
}
.cat-tile-overlay {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
    background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.6) 100%);
}
.cat-tile-content {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: var(--space-lg);
    z-index: 5;
}
.cat-tile-title {
    font-family: var(--font-sans);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--color-pure-white);
    margin-bottom: 4px;
}
.cat-tile-cta {
    font-family: var(--font-sans);
    font-size: 0.65rem;
    font-weight: 600;
    color: var(--color-pure-white);
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: underline;
    text-underline-offset: 3px;
}

/* VALUE PROPS */
.value-props {
    display: flex;
    align-items: center;
    justify-content: space-around;
    background: var(--color-pure-white);
    border: 1px solid var(--color-light-gray);
    border-radius: var(--radius-md);
    padding: var(--space-lg) var(--space-md);
}
.value-prop {
    text-align: center;
    flex: 1;
}
.value-prop-icon {
    font-size: 2rem;
    color: var(--color-rich-black);
    margin-bottom: 6px;
    display: block;
}
.value-prop-title {
    font-family: var(--font-sans);
    font-size: 0.65rem;
    font-weight: 600;
    color: var(--color-primary-text);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.value-prop-desc {
    font-family: var(--font-sans);
    font-size: 0.6rem;
    color: var(--color-paragraph-text);
    line-height: 1.3;
}
.value-prop-divider {
    width: 1px;
    height: 40px;
    background: var(--color-light-gray);
    flex-shrink: 0;
}
@media (min-width: 769px) {
    .value-prop-icon { font-size: 2.5rem; }
    .value-prop-title { font-size: 0.8rem; }
    .value-prop-desc { font-size: 0.75rem; }
    .value-prop-divider { height: 55px; }
}
@media (max-width: 480px) {
    .value-props { flex-wrap: wrap; padding: var(--space-md); gap: var(--space-md); }
    .value-prop { flex: 0 0 45%; }
    .value-prop-divider { display: none; }
}

/* SECTION TITLE UNDERLINE (gold bar under heading) */
.section-title-underline {
    width: 40px;
    height: 3px;
    background: var(--color-premium-gold);
    margin-top: 4px;
}

/* NEW ARRIVALS GRID */
.new-arrivals-grid {
    display: flex;
    gap: var(--space-md);
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    padding-bottom: var(--space-sm);
}
.new-arrivals-grid::-webkit-scrollbar { display: none; }
.new-arrivals-item {
    flex: 0 0 calc(50% - 8px);
    min-width: 150px;
    scroll-snap-align: start;
}
@media (min-width: 769px) {
    .new-arrivals-grid {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr);
        overflow-x: visible;
    }
    .new-arrivals-item {
        flex: unset;
        min-width: 0;
    }
}

/* PROMO BANNER */
.promo-banner {
    background: var(--color-rich-black);
    border-radius: var(--radius-lg);
    padding: var(--space-lg) var(--space-xl);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-md);
    border: 1px solid rgba(200, 155, 60, 0.2);
}
.promo-left {
    display: flex;
    align-items: center;
    gap: var(--space-md);
}
.promo-icon {
    color: var(--color-premium-gold);
    flex-shrink: 0;
}
.promo-icon .material-symbols-outlined {
    font-size: 2.2rem;
}
.promo-heading {
    font-family: var(--font-sans);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-premium-gold);
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.promo-desc {
    font-family: var(--font-sans);
    font-size: 0.8rem;
    color: var(--color-medium-gray);
}
.promo-btn {
    flex-shrink: 0;
    padding: 12px 28px;
}
@media (max-width: 600px) {
    .promo-banner {
        flex-direction: column;
        text-align: center;
        padding: var(--space-lg);
    }
    .promo-left {
        flex-direction: column;
    }
}
</style>

@endsection
