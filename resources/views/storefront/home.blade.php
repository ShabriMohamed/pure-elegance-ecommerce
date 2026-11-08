@extends('layouts.app')

@section('title', 'Home')
@section('meta_description', 'Pure Elegance — original branded fashion for Men & Women. Curated collections, islandwide delivery and secure checkout.')

@push('styles')
    @vite('resources/css/home.css')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                'name'  => 'Pure Elegance',
                'url'   => url('/'),
                'logo'  => asset('favicon.svg'),
                'email' => site('contact_email', 'info@pureelegance.lk'),
                'telephone' => site('contact_phone', '+94 77 123 4567'),
            ],
            [
                '@type' => 'WebSite',
                'name'  => 'Pure Elegance',
                'url'   => url('/'),
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => route('search') . '?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
@endpush

@section('content')

{{-- Scroll-reveal is opt-in: the CSS only hides `.pe-reveal` while <html> carries
     data-reveal. Setting it here — before the sections below are parsed — means
     no flash of visible-then-hidden content, and a browser with JS off simply
     renders the whole page.

     The timeout is the failsafe: home.js sets data-reveal-active from inside the
     observer's first callback, so if the script never loads — or loads somewhere
     IntersectionObserver never fires — the attribute is stripped and the page
     shows in full rather than staying at opacity 0. --}}
<script>
    (function () {
        var root = document.documentElement;
        root.setAttribute('data-reveal', '');
        setTimeout(function () {
            if (!root.hasAttribute('data-reveal-active')) root.removeAttribute('data-reveal');
        }, 1200);
    })();
</script>

@php
    /* ------------------------------------------------------------------
       Hero slides — built from the admin-managed banners, with a curated
       fallback set so the hero is never a single static frame on a fresh
       install. Each banner may carry its own description; the generic line
       is only used when it has none.
    ------------------------------------------------------------------ */
    $heroSlides = $banners->map(fn ($banner) => [
        'eyebrow'     => $banner->subtitle ?: 'The new signature collection',
        'title'       => $banner->title ?: 'Timeless style. Made distinctly yours.',
        'description' => 'Premium pieces selected for modern living, effortless confidence and everyday ease.',
        'image'       => $banner->image_url,
        'cta_text'    => $banner->cta_text ?: 'Shop the collection',
        'cta_link'    => $banner->cta_link ?: route('categories'),
    ])->values();

    if ($heroSlides->count() < 2) {
        $heroSlides = collect([
            [
                'eyebrow'     => 'Step into style',
                'title'       => 'Timeless fashion. Pure elegance.',
                'description' => 'Premium fashion for Men & Women. Elevate your everyday style.',
                'image'       => asset('images/hero-banner.webp'),
                'cta_text'    => 'Shop now',
                'cta_link'    => route('categories'),
            ],
            [
                'eyebrow'     => 'New season',
                'title'       => 'Wear your confidence.',
                'description' => 'Modern silhouettes and considered essentials for every occasion.',
                'image'       => asset('images/category-men.webp'),
                'cta_text'    => 'Explore men',
                'cta_link'    => route('categories', ['gender' => 'men']),
            ],
            [
                'eyebrow'     => 'The women’s edit',
                'title'       => 'Elevate your everyday.',
                'description' => 'Refined pieces designed to move with your life and your style.',
                'image'       => asset('images/category-women.webp'),
                'cta_text'    => 'Explore women',
                'cta_link'    => route('categories', ['gender' => 'women']),
            ],
        ]);
    }

    $slideCount = $heroSlides->count();
@endphp

<div class="pe-landing">

{{-- ==========================================================================
     HERO — full-bleed editorial carousel
     The image runs edge to edge with no gutter, radius or shadow. The copy
     inside rides `.container`, so the headline still lines up with every
     section below it while the artwork fills the screen.
     ========================================================================== --}}
<section class="pe-hero"
         id="hero"
         data-hero
         aria-roledescription="carousel"
         aria-label="Pure Elegance highlights">
        <div class="pe-hero__viewport">
            @foreach($heroSlides as $index => $slide)
                <article class="pe-hero__slide {{ $index === 0 ? 'is-active' : '' }}"
                         data-hero-slide
                         aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                         aria-roledescription="slide"
                         aria-label="{{ $index + 1 }} of {{ $slideCount }}">

                    <div class="pe-hero__visual">
                        <img src="{{ $slide['image'] }}"
                             alt=""
                             width="1600" height="1000"
                             fetchpriority="{{ $index === 0 ? 'high' : 'low' }}"
                             loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                             decoding="async">
                    </div>
                    <div class="pe-hero__grain" aria-hidden="true"></div>

                    <div class="pe-hero__index" aria-hidden="true">
                        <span>PURE ELEGANCE</span><i></i><span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    {{-- `.container` here is what keeps the headline on the same
                         grid as the sections below, now that the artwork itself
                         is full-bleed. --}}
                    <div class="pe-hero__inner container">
                        <div class="pe-hero__copy">
                            <p class="pe-hero__eyebrow pe-hero__rise" style="--d:0">
                                <i aria-hidden="true"></i>{{ $slide['eyebrow'] }}
                            </p>

                            {{-- Only the first slide is an <h1>; the rest are <h2> so the
                                 page keeps exactly one top-level heading. --}}
                            @if($index === 0)
                                <h1 class="pe-hero__title pe-hero__rise" style="--d:1">{{ $slide['title'] }}</h1>
                            @else
                                <h2 class="pe-hero__title pe-hero__rise" style="--d:1">{{ $slide['title'] }}</h2>
                            @endif

                            <p class="pe-hero__description pe-hero__rise" style="--d:2">{{ $slide['description'] }}</p>

                            <div class="pe-hero__actions pe-hero__rise" style="--d:3">
                                <a href="{{ $slide['cta_link'] }}" class="pe-hero__cta">
                                    <span>{{ $slide['cta_text'] }}</span>
                                    <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                                </a>
                                <a href="{{ route('new-arrivals') }}" class="pe-hero__link">
                                    Explore new arrivals
                                    <span class="material-symbols-outlined" aria-hidden="true">north_east</span>
                                </a>
                            </div>

                            <div class="pe-hero__proof pe-hero__rise" style="--d:4">
                                <span class="pe-hero__proof-mark" aria-hidden="true">PE</span>
                                <span>
                                    <strong>Original pieces, considered carefully.</strong>
                                    <small>Islandwide delivery · Secure checkout</small>
                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ $slide['cta_link'] }}" class="pe-hero__card">
                        <small>Just landed</small>
                        <strong>Modern icons,<br>made to last.</strong>
                        <span>Discover now <b aria-hidden="true">&rarr;</b></span>
                    </a>
                </article>
            @endforeach

            @if($slideCount > 1)
                <div class="pe-hero__controls">
                    <button type="button" class="pe-hero__arrow" data-hero-prev aria-label="Previous highlight">
                        <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                    </button>
                    <div class="pe-hero__dots" role="tablist" aria-label="Choose a highlight">
                        @foreach($heroSlides as $index => $slide)
                            <button type="button"
                                    class="pe-hero__dot {{ $index === 0 ? 'is-active' : '' }}"
                                    data-hero-dot="{{ $index }}"
                                    role="tab"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Show highlight {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <button type="button" class="pe-hero__arrow" data-hero-next aria-label="Next highlight">
                        <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                    </button>
                    <span class="pe-hero__progress" aria-hidden="true"><i data-hero-progress></i></span>
                </div>

                {{-- Slide changes are announced here rather than on the slides
                     themselves, so a screen reader gets one calm update. --}}
                <p class="sr-only" aria-live="polite" data-hero-live>Highlight 1 of {{ $slideCount }}</p>
            @endif
        </div>
</section>


{{-- ==========================================================================
     ASSURANCE BAR
     ========================================================================== --}}
<section class="pe-section pe-section--tight" aria-label="Why shop with Pure Elegance">
    <div class="container">
        @php
            $assurances = [
                ['icon' => 'local_shipping',      'title' => site('feature_1_title', 'Cash on delivery'), 'desc' => site('feature_1_subtitle', 'Islandwide delivery')],
                ['icon' => 'workspace_premium',   'title' => site('feature_2_title', '100% original'),    'desc' => site('feature_2_subtitle', 'Branded products')],
                ['icon' => 'sync_alt',            'title' => site('feature_3_title', 'Easy returns'),     'desc' => site('feature_3_subtitle', '7 day return policy')],
                ['icon' => 'lock',                'title' => site('feature_4_title', 'Secure payment'),   'desc' => site('feature_4_subtitle', 'Safe & secure checkout')],
            ];
        @endphp
        <div class="pe-assurance pe-reveal">
            @foreach($assurances as $item)
                <div class="pe-assurance__item">
                    <span class="pe-assurance__icon">
                        <span class="material-symbols-outlined" aria-hidden="true">{{ $item['icon'] }}</span>
                    </span>
                    <span>
                        <span class="pe-assurance__title">{{ $item['title'] }}</span>
                        <span class="pe-assurance__desc">{{ $item['desc'] }}</span>
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ==========================================================================
     COLLECTIONS — Men / Women / Outlet
     ========================================================================== --}}
<section class="pe-section pe-section--flush-top" id="collections">
    <div class="container">
        <div class="pe-head pe-reveal">
            <div>
                <p class="pe-head__eyebrow">The edit</p>
                <h2 class="pe-head__title">Shop by <em>collection</em></h2>
            </div>
            <a href="{{ route('categories') }}" class="pe-head__link">
                All collections <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>

        @php
            $collections = [
                ['title' => 'Men',    'href' => route('categories', ['gender' => 'men']),   'img' => asset('images/category-men.webp'),    'cta' => 'Shop now',        'alt' => "Men's collection"],
                ['title' => 'Women',  'href' => route('categories', ['gender' => 'women']), 'img' => asset('images/category-women.webp'),  'cta' => 'Shop now',        'alt' => "Women's collection"],
                ['title' => 'Outlet', 'href' => route('sale'),                              'img' => asset('images/category-outlet.webp'), 'cta' => 'Up to 60% off',   'alt' => 'Outlet sale'],
            ];
        @endphp
        <div class="pe-collections">
            @foreach($collections as $index => $collection)
                <a href="{{ $collection['href'] }}" class="pe-collection pe-reveal" style="--i:{{ $index }}">
                    <img src="{{ $collection['img'] }}"
                         alt="{{ $collection['alt'] }}"
                         class="pe-collection__img"
                         width="600" height="420"
                         loading="lazy" decoding="async">
                    <span class="pe-collection__scrim" aria-hidden="true"></span>
                    <span class="pe-collection__num" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="pe-collection__body">
                        <span class="pe-collection__title">{{ $collection['title'] }}</span>
                        <span class="pe-collection__rule" aria-hidden="true"></span>
                        <span class="pe-collection__cta">
                            {{ $collection['cta'] }}
                            <span class="material-symbols-outlined" aria-hidden="true">north_east</span>
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ==========================================================================
     SHOP BY CATEGORY
     ========================================================================== --}}
@if(isset($topCategories) && $topCategories->count() > 0)
<section class="pe-section pe-section--flush-top" id="shop-by-category">
    <div class="container">
        <div class="pe-head pe-head--center pe-reveal">
            <div>
                <p class="pe-head__eyebrow">Browse the catalogue</p>
                <h2 class="pe-head__title">Shop by category</h2>
                <p class="pe-head__sub">Every department we stock, with what is in it right now.</p>
            </div>
        </div>

        @php
            /* Material Symbols renders an unknown ligature as its literal text —
               "cosmetics" is not a symbol and was printing the raw word across a
               tile. So the DB value is checked against a known-good allowlist
               rather than a shape regex. */
            $validIcons = [
                'man', 'woman', 'sell', 'checkroom', 'apparel', 'steps', 'watch',
                'diamond', 'spa', 'shopping_bag', 'local_mall', 'health_and_beauty',
                'brush', 'styler', 'face_retouching_natural', 'dermatology',
                'headphones', 'redeem', 'auto_awesome', 'category',
            ];

            /* Keyed on words rather than slugs: the tiles are second-level
               categories, so their slugs are prefixed ("men-clothing",
               "women-watches") and a slug lookup would miss every one of them.
               First match against the lowercased name wins, so order matters —
               "skin care" is checked before the broader "beauty". */
            $iconByKeyword = [
                'skin'        => 'dermatology',
                'makeup'      => 'brush',
                'beauty'      => 'health_and_beauty',
                'fragrance'   => 'spa',
                'footwear'    => 'steps',
                'shoe'        => 'steps',
                'sneaker'     => 'steps',
                'watch'       => 'watch',
                'bag'         => 'shopping_bag',
                'accessor'    => 'diamond',
                'jewel'       => 'diamond',
                'cloth'       => 'checkroom',
                'shirt'       => 'checkroom',
                'dress'       => 'checkroom',
                'tech'        => 'headphones',
                'sale'        => 'sell',
                'outlet'      => 'sell',
                'men'         => 'man',
                'women'       => 'woman',
            ];
        @endphp

        <div class="pe-categories">
            @foreach($topCategories as $index => $category)
                @php
                    $slug = strtolower($category->slug);
                    $icon = null;

                    if (in_array($category->icon, $validIcons, true)) {
                        $icon = $category->icon;
                    } else {
                        $haystack = strtolower($category->name);
                        foreach ($iconByKeyword as $needle => $symbol) {
                            if (str_contains($haystack, $needle)) {
                                $icon = $symbol;
                                break;
                            }
                        }
                    }
                    $icon ??= 'category';

                    // Sale is price-driven, so its tile goes to the dedicated /sale page.
                    $href = $slug === 'sale' ? route('sale') : route('category.show', $category->slug);
                    $soon = $category->products_count < 1;
                    $tag  = $soon ? 'div' : 'a';
                @endphp
                {{-- Empty categories render as a plain tile, so none links to an empty listing. --}}
                <{{ $tag }} @unless($soon) href="{{ $href }}" @endunless
                    class="pe-category pe-reveal {{ $soon ? 'pe-category--soon' : '' }}" style="--i:{{ min($index, 5) }}">
                    <span class="pe-category__icon">
                        <span class="material-symbols-outlined" aria-hidden="true">{{ $icon }}</span>
                    </span>
                    <div class="shop-cat-name">{{ $category->name }}</div>
                    <div class="pe-category__count">
                        @if($category->parent_label)
                            <span class="pe-category__parent">{{ $category->parent_label }}</span>
                        @endif
                        @if($soon)
                            <span class="pe-category__soon">Coming soon</span>
                        @endif
                    </div>
                </{{ $tag }}>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ==========================================================================
     FEATURED
     ========================================================================== --}}
@if($featuredProducts->count() > 0)
<section class="pe-section pe-section--beige" id="featured-section">
    <div class="container">
        <div class="pe-head pe-reveal">
            <div>
                <p class="pe-head__eyebrow">Handpicked</p>
                <h2 class="pe-head__title">Featured <em>pieces</em></h2>
                <p class="pe-head__sub">The pieces our team keeps coming back to — chosen for cut, material and the way they wear.</p>
            </div>
            <a href="{{ route('categories') }}" class="pe-head__link">
                View all <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>

        <div class="pe-products pe-products--fill">
            @foreach($featuredProducts as $index => $product)
                <div class="pe-reveal" style="--i:{{ $index % 4 }}">
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


{{-- ==========================================================================
     SHOP BY BRAND — auto-scrolling marquee
     The brand set is rendered 3x so the CSS loop (translateX -33.333%) is
     seamless; copies past the first are hidden from AT and the tab order.
     ========================================================================== --}}
@if($brands->count() > 0)
<section class="pe-brands" aria-labelledby="brands-heading">
    <div class="container">
        <div class="pe-head pe-head--center pe-reveal">
            <div>
                <p class="pe-head__eyebrow">Trusted labels</p>
                <h2 class="pe-head__title" id="brands-heading">Shop by Brand</h2>
            </div>
        </div>
    </div>

    <div class="pe-marquee">
        <div class="pe-marquee__track">
            @for($rep = 0; $rep < 3; $rep++)
                @foreach($brands as $brand)
                    @php $logo = 'images/brands/' . Str::slug($brand->brand) . '.png'; @endphp
                    <a href="{{ route('categories', ['brand' => $brand->brand]) }}"
                       class="pe-marquee__item"
                       @if($rep > 0) aria-hidden="true" tabindex="-1" @else aria-label="Shop {{ $brand->brand }}" @endif>
                        {{-- No logo file → typeset wordmark, never a third-party placeholder service. --}}
                        @if(file_exists(public_path($logo)))
                            <img src="{{ asset($logo) }}" alt="" width="150" height="50" loading="lazy" decoding="async">
                        @else
                            <span class="pe-marquee__word">{{ $brand->brand }}</span>
                        @endif
                    </a>
                @endforeach
            @endfor
        </div>
    </div>
</section>
@endif


{{-- ==========================================================================
     BRAND STORY
     ========================================================================== --}}
<section class="pe-story" id="our-story">
    <div class="container">
        <div class="pe-story__grid">
            <div class="pe-reveal">
                <p class="pe-head__eyebrow">Our story</p>
                <h2 class="pe-head__title">Discover original<br>branded fashion</h2>
                <p class="pe-story__text">
                    At Pure Elegance we believe fashion is more than clothing — it is a statement of who you are.
                    We curate original branded pieces from the finest designers, bringing you premium fashion that
                    pairs timeless elegance with contemporary style. Every item is handpicked for authenticity,
                    quality and the perfect fit.
                </p>

                <dl class="pe-story__stats">
                    {{-- Real count only; a "1+" or invented number reads worse than no stat. --}}
                    @if($brands->count() >= 5)
                        <div class="pe-story__stat">
                            <dt>{{ $brands->count() }}+</dt>
                            <dd>Labels curated</dd>
                        </div>
                    @else
                        <div class="pe-story__stat">
                            <dt>COD</dt>
                            <dd>Islandwide</dd>
                        </div>
                    @endif
                    <div class="pe-story__stat">
                        <dt>100%</dt>
                        <dd>Original pieces</dd>
                    </div>
                    <div class="pe-story__stat">
                        <dt>7 days</dt>
                        <dd>Easy returns</dd>
                    </div>
                </dl>

                <a href="{{ route('categories') }}" class="pe-story__btn">
                    Explore collection
                    <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                </a>
            </div>

            <div class="pe-story__figure pe-reveal" style="--i:1">
                <img src="{{ asset('images/hero-banner.webp') }}"
                     alt="Pure Elegance curated rail"
                     width="800" height="1000"
                     loading="lazy" decoding="async">
                <div class="pe-story__badge">
                    <strong>Pure Elegance</strong>
                    <span>Premium fashion since 2024</span>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ==========================================================================
     NEW ARRIVALS
     ========================================================================== --}}
@if($newArrivals->count() > 0)
<section class="pe-section" id="new-arrivals-section">
    <div class="container">
        <div class="pe-head pe-reveal">
            <div>
                <p class="pe-head__eyebrow">Just landed</p>
                <h2 class="pe-head__title">New <em>arrivals</em></h2>
                <p class="pe-head__sub">Fresh in this week, while sizes last.</p>
            </div>
            <a href="{{ route('new-arrivals') }}" class="pe-head__link">
                View all <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>

        <div class="pe-products pe-products--fill">
            @foreach($newArrivals as $index => $product)
                <div class="pe-reveal" style="--i:{{ $index % 4 }}">
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


{{-- ==========================================================================
     MEMBERSHIP PROMO
     ========================================================================== --}}
@guest
<section class="pe-section pe-section--flush-top">
    <div class="container">
        <div class="pe-promo pe-reveal">
            <div class="pe-promo__left">
                <span class="pe-promo__icon">
                    <span class="material-symbols-outlined" aria-hidden="true">featured_seasonal_and_gifts</span>
                </span>
                <div>
                    <div class="pe-promo__heading">{{ site('promo_banner_heading', 'Join for exclusive offers') }}</div>
                    <p class="pe-promo__desc">{{ site('promo_banner_text', 'Create an account and be first to hear about new drops and member deals.') }}</p>
                </div>
            </div>
            <a href="{{ route('register') }}" class="pe-promo__btn">
                Join now <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
@endguest


{{-- ==========================================================================
     NEWSLETTER
     ========================================================================== --}}
<section class="pe-newsletter" id="newsletter">
    <div class="container">
        <div class="pe-newsletter__inner pe-reveal">
            <p class="pe-newsletter__eyebrow">Stay in the loop</p>
            <h2 class="pe-newsletter__heading">{{ site('newsletter_title', 'Get the latest drops straight to your inbox') }}</h2>
            <p class="pe-newsletter__desc">{{ site('newsletter_subtitle', 'Be the first to know about new collections, exclusive deals and style inspiration.') }}</p>

            {{-- The result is rendered here, beside the form that produced it.
                 The controller redirects back to #newsletter so the confirmation
                 is on screen instead of ~6000px above at the top of <main>. --}}
            @if(session('newsletter_status'))
                <p class="pe-newsletter__status pe-newsletter__status--ok" role="status">
                    <span class="material-symbols-outlined" aria-hidden="true">check_circle</span>
                    {{ session('newsletter_status') }}
                </p>
            @endif
            @error('email')
                <p class="pe-newsletter__status pe-newsletter__status--error" role="alert">
                    <span class="material-symbols-outlined" aria-hidden="true">error</span>
                    {{ $message }}
                </p>
            @enderror

            <form class="pe-newsletter__form" method="POST" action="{{ route('newsletter.store') }}" data-newsletter>
                @csrf
                <label class="sr-only" for="newsletter-email">Email address</label>
                <input type="email"
                       id="newsletter-email"
                       name="email"
                       class="pe-newsletter__input"
                       placeholder="Enter your email address"
                       value="{{ old('email') }}"
                       autocomplete="email"
                       @error('email') aria-invalid="true" aria-describedby="newsletter-error" @enderror
                       required>
                {{-- Honeypot: hidden from real users; bots that fill it are silently dropped. --}}
                <input type="text" name="website" tabindex="-1" autocomplete="off" class="sr-only" aria-hidden="true">
                <button type="submit" class="pe-newsletter__submit">Subscribe</button>
            </form>

            <p class="pe-newsletter__trust">
                <span class="material-symbols-outlined" aria-hidden="true">lock</span>
                No spam. Unsubscribe anytime.
            </p>
        </div>
    </div>
</section>

</div>{{-- /.pe-landing --}}

@vite('resources/js/home.js')

@endsection
