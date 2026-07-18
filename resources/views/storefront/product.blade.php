@extends('layouts.app')

@section('title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: Str::limit(strip_tags($product->short_description ?: $product->description), 155))

@section('content')
<div class="container" style="padding-top: var(--space-lg); padding-bottom: var(--space-2xl);">
    
    {{-- Breadcrumb --}}
    <nav class="font-body" style="font-size: 0.8rem; color: var(--color-muted-text); margin-bottom: var(--space-lg);">
        <a href="{{ route('home') }}" style="color: var(--color-muted-text);">Home</a>
        <span style="margin: 0 6px;">/</span>
        @if($product->category)
            <a href="{{ route('category.show', $product->category->slug) }}" style="color: var(--color-muted-text);">{{ $product->category->name }}</a>
            <span style="margin: 0 6px;">/</span>
        @endif
        <span style="color: var(--color-primary-text); font-weight: 500;">{{ $product->name }}</span>
    </nav>

    <div class="grid md-grid-cols-2" style="gap: var(--space-2xl); align-items: start;">
        
        {{-- Product Image Gallery --}}
        <div>
            <div style="background: var(--color-soft-gray); border-radius: var(--radius-md); overflow: hidden; margin-bottom: var(--space-sm); position: relative;">
                @if($product->is_new_arrival)
                    <div class="product-badge" style="background: var(--color-rich-black); top: var(--space-md); left: var(--space-md); font-size: 0.75rem; padding: 4px 12px;">NEW</div>
                @elseif($product->is_on_sale)
                    <div class="product-badge" style="background: var(--color-error); top: var(--space-md); left: var(--space-md); font-size: 0.75rem; padding: 4px 12px;">SALE</div>
                @endif
                <img id="main-product-image" 
                     src="{{ $product->primary_image_url }}" 
                     alt="{{ $product->name }}" 
                     style="width: 100%; aspect-ratio: 1/1; object-fit: cover; display: block;">
            </div>
            
            @if($product->images && $product->images->count() > 1)
                <div class="scroll-row" style="gap: var(--space-sm);">
                    @foreach($product->images as $image)
                        <div style="width: 80px; height: 100px; background: var(--color-soft-gray); cursor: pointer; border: 2px solid {{ $loop->first ? 'var(--color-premium-gold)' : 'transparent' }}; border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0; transition: border-color var(--transition-base);"
                             onclick="document.getElementById('main-product-image').src='{{ $image->url }}'; this.parentElement.querySelectorAll('div').forEach(function(el){el.style.borderColor='transparent'}); this.style.borderColor='var(--color-premium-gold)';">
                            <img src="{{ $image->url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div style="padding-top: var(--space-sm);">
            <div class="font-caps" style="font-size: 0.75rem; color: var(--color-muted-text); letter-spacing: 1.5px; margin-bottom: var(--space-xs);">
                {{ $product->brand ?? 'PURE ELEGANCE' }}
            </div>
            
            <h1 class="font-h1" style="font-size: clamp(1.5rem, 3vw, 2.2rem); line-height: 1.25; margin-bottom: var(--space-md);">
                {{ $product->name }}
            </h1>
            
            <div class="font-h3" style="font-size: 1.4rem; margin-bottom: var(--space-lg); display: flex; align-items: center; gap: var(--space-sm);">
                @if($product->is_on_sale)
                    <span style="color: var(--color-error);">{{ money($product->sale_price) }}</span>
                    <span style="text-decoration: line-through; color: var(--color-muted-text); font-size: 1rem; font-weight: 400;">{{ money($product->price) }}</span>
                @else
                    <span style="color: var(--color-primary-text);">{{ money($product->price) }}</span>
                @endif
            </div>

            @if($product->short_description || $product->description)
                <p class="font-body" style="font-size: 0.9rem; color: var(--color-paragraph-text); line-height: 1.7; margin-bottom: var(--space-xl); border-top: 1px solid var(--color-border); padding-top: var(--space-lg);">
                    {{ $product->short_description ?? $product->description }}
                </p>
            @endif

            {{-- Actions --}}
            <div style="margin-top: var(--space-lg);">
                <form method="POST" action="{{ route('cart.add') }}" style="margin-bottom: var(--space-md);">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    @if($product->variants && $product->variants->isNotEmpty())
                        <div class="form-group">
                            <label class="form-label" style="text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; font-weight: 600;">Size / Variant</label>
                            <select name="variant_id" class="form-control" style="height: 48px; cursor: pointer;" required>
                                <option value="" disabled selected>Choose an option</option>
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}" {{ $variant->stock_quantity <= 0 ? 'disabled' : '' }}>
                                        {{ $variant->size }}{{ $variant->color ? ' - ' . $variant->color : '' }}
                                        @if($variant->price_adjustment > 0) (+{{ money($variant->price_adjustment) }}) @endif
                                        {{ $variant->stock_quantity <= 0 ? ' (Out of Stock)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div style="display: flex; gap: var(--space-md);">
                        <div style="width: 100px;">
                            <input type="number" name="quantity" value="1" min="1" max="{{ max(1, min((int) $product->stock_quantity, (int) config('shop.max_qty_per_line'))) }}" class="form-control" style="text-align: center; height: 48px; font-weight: 600; font-size: 1rem; background: var(--color-soft-gray); border: 1px solid var(--color-border);">
                        </div>
                        <button type="submit" class="btn btn-primary" style="flex: 1; height: 48px; font-size: 0.85rem; letter-spacing: 1.5px;">
                            ADD TO CART
                            <span class="material-symbols-outlined" style="font-size: 1.2rem;">shopping_bag</span>
                        </button>
                    </div>
                </form>

                @php
                    $inWishlist = false;
                    if(Auth::check()) {
                        $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->exists();
                    }
                @endphp
                <button type="button" 
                        class="btn btn-outline" 
                        style="width: 100%; height: 48px; font-size: 0.85rem; letter-spacing: 1.5px; border-color: var(--color-border);"
                        onclick="@auth toggleWishlist({{ $product->id }}) @else window.location.href='{{ route('login') }}' @endauth">
                    <span class="material-symbols-outlined" style="font-size: 1.2rem; {{ $inWishlist ? 'color: var(--color-error);' : '' }}">
                        {{ $inWishlist ? 'favorite' : 'favorite_border' }}
                    </span>
                    <span style="margin-left: 8px;">ADD TO WISHLIST</span>
                </button>
            </div>

            {{-- Trust Features --}}
            <div style="margin-top: var(--space-2xl); display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="color: var(--gold-text); font-size: 1.4rem;">local_shipping</span>
                    <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">Free Delivery<br><span style="color: var(--color-muted-text); font-weight: 400;">Over {{ money(\App\Support\DeliveryFee::threshold()) }}</span></div>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="color: var(--gold-text); font-size: 1.4rem;">verified</span>
                    <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">100% Authentic<br><span style="color: var(--color-muted-text); font-weight: 400;">Genuine Products</span></div>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="color: var(--gold-text); font-size: 1.4rem;">360</span>
                    <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">Easy Returns<br><span style="color: var(--color-muted-text); font-weight: 400;">7 Day Policy</span></div>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="color: var(--gold-text); font-size: 1.4rem;">lock</span>
                    <div class="font-body" style="font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">Secure Payment<br><span style="color: var(--color-muted-text); font-weight: 400;">Safe Checkout</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================ REVIEWS ============================ --}}
    <div id="reviews" style="margin-top: var(--space-3xl); scroll-margin-top: 96px;">
        <h2 class="font-h2" style="font-size: 1.4rem; margin-bottom: var(--space-lg); display: flex; align-items: center; gap: var(--space-md); flex-wrap: wrap;">
            Customer Reviews
            @if($reviewCount > 0)
                <span style="display: inline-flex; align-items: center; gap: 6px; font-family: var(--font-sans); font-size: 0.95rem; font-weight: 500;">
                    <span class="product-stars" style="--rating: {{ $averageRating }}; font-size: 1rem;" aria-hidden="true">★★★★★</span>
                    <span style="color: var(--color-muted-text);">{{ number_format($averageRating, 1) }} · {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
                </span>
            @endif
        </h2>

        {{-- Write / edit a review --}}
        @auth
            <div class="review-form-card">
                <div class="review-form-head">
                    <span class="review-form-icon material-symbols-outlined">rate_review</span>
                    <div>
                        <h3 class="review-form-title">{{ $userReview ? 'Update your review' : 'Write a review' }}</h3>
                        <p class="review-form-sub">Tell other shoppers what you think of {{ $product->name }}.</p>
                    </div>
                </div>

                @if($userReview && !$userReview->is_approved)
                    <div class="alert alert-success" style="margin-bottom: var(--space-md);">
                        <span class="material-symbols-outlined">schedule</span> Your review is awaiting approval.
                    </div>
                @endif

                <form method="POST" action="{{ route('product.reviews.store', $product) }}">
                    @csrf

                    {{-- Star rating: radio inputs styled as stars. Reversed in the DOM so a
                         pure-CSS sibling selector can fill every star to the left of the
                         hovered/checked one — works with keyboard and without JS. --}}
                    <div class="form-group">
                        <span class="form-label" id="rating-label">Your rating</span>
                        <div class="star-input" role="radiogroup" aria-labelledby="rating-label">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                                       {{ (int) old('rating', optional($userReview)->rating ?? 5) === $i ? 'checked' : '' }} required>
                                <label for="star{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                    <span class="material-symbols-outlined">star</span>
                                    <span class="sr-only">{{ $i }} star{{ $i > 1 ? 's' : '' }}</span>
                                </label>
                            @endfor
                        </div>
                        @error('rating')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="review-comment">Your review <span class="review-optional">(optional)</span></label>
                        <textarea name="comment" id="review-comment" class="form-control review-textarea" rows="4"
                                  maxlength="1000" placeholder="What did you like? How was the fit and quality?">{{ old('comment', optional($userReview)->comment) }}</textarea>
                        @error('comment')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="review-form-actions">
                        <button type="submit" class="btn btn-primary">
                            {{ $userReview ? 'Update review' : 'Submit review' }}
                        </button>
                        <span class="review-form-note">
                            <span class="material-symbols-outlined">verified_user</span>
                            Reviews are checked before publishing
                        </span>
                    </div>
                </form>
            </div>
        @else
            {{-- Guests previously got a single muted sentence that was easy to miss
                 entirely on mobile. This is a proper, visible invitation. --}}
            <div class="review-login-card">
                <span class="review-login-stars" aria-hidden="true">★★★★★</span>
                <h3 class="review-login-title">Share your thoughts</h3>
                <p class="review-login-sub">Sign in to rate and review this product.</p>
                <div class="review-login-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary">Log in to review</a>
                    <a href="{{ route('register') }}" class="review-login-link">Create an account</a>
                </div>
            </div>
        @endauth

        {{-- Approved reviews --}}
        @forelse($reviews as $review)
            <div style="padding: var(--space-md) 0; border-bottom: 1px solid var(--color-border);">
                <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: 4px;">
                    <span class="product-stars" style="--rating: {{ $review->rating }};" aria-hidden="true">★★★★★</span>
                    <strong style="font-size: 0.9rem;">{{ $review->user->name ?? 'Customer' }}</strong>
                    <span style="color: var(--color-muted-text); font-size: 0.75rem;">{{ $review->created_at->format('M d, Y') }}</span>
                </div>
                @if($review->comment)
                    <p style="color: var(--color-paragraph-text); font-size: 0.9rem; line-height: 1.6;">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p style="color: var(--color-muted-text); font-size: 0.9rem;">No reviews yet. Be the first to review this product.</p>
        @endforelse

        @if($reviews->hasPages())
            <div style="margin-top: var(--space-lg);">{{ $reviews->links() }}</div>
        @endif
    </div>

    {{-- ============================ RELATED ============================ --}}
    @if($relatedProducts->count() > 0)
        <div style="margin-top: var(--space-3xl);">
            <div class="section-header">
                <div>
                    <h2 class="section-title" style="font-size: 1.4rem;">You May Also Like</h2>
                    <div class="section-title-underline"></div>
                </div>
            </div>
            <div class="home-product-grid">
                @foreach($relatedProducts as $related)
                    <div class="home-product-item">
                        @include('storefront.partials.product-card', ['product' => $related])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
