{{-- Product Card Component
    Accepts:
      $product      - Product model instance
      $wishlistIds  - (optional) array of product IDs in the user's wishlist
                      Passed from the controller to avoid N+1 queries.
    Matches UI: White bg card, product image top, heart icon top-right,
    product name, price at bottom. Sale items show SALE badge + prices. --}}
<a href="{{ route('product.show', $product->slug) }}" class="product-card" id="product-card-{{ $product->id }}">
    <div class="product-img-wrapper">
        @if($product->is_on_sale)
            <div class="product-badge" style="background: var(--color-error);">SALE</div>
        @elseif($product->is_new_arrival)
            <div class="product-badge" style="background: var(--color-rich-black);">NEW</div>
        @endif

        <img src="{{ $product->primary_image_url }}"
             alt="{{ $product->name }}"
             class="product-img"
             loading="lazy">

        @php
            if (isset($wishlistIds)) {
                $inWishlist = in_array($product->id, (array) $wishlistIds);
            } elseif (Auth::check()) {
                $inWishlist = \App\Models\Wishlist::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->exists();
            } else {
                $inWishlist = false;
            }
        @endphp

        <button class="product-wishlist-btn"
                onclick="event.preventDefault(); event.stopPropagation(); @auth toggleWishlist({{ $product->id }}) @else window.location.href='{{ route('login') }}' @endauth"
                aria-label="Toggle wishlist">
            <span class="material-symbols-outlined" style="{{ $inWishlist ? 'color: var(--color-error);' : 'color: var(--color-medium-gray);' }}">
                {{ $inWishlist ? 'favorite' : 'favorite_border' }}
            </span>
        </button>
    </div>
    <div class="product-info">
        <div class="product-title">{{ $product->name }}</div>
        <div class="product-price">
            @if($product->is_on_sale)
                <span class="price-sale">{{ money($product->sale_price) }}</span>
                <span class="price-original">{{ money($product->price) }}</span>
            @else
                <span>{{ money($product->price) }}</span>
            @endif
        </div>

        @php
            // Prefer eager-loaded aggregates (scopeWithRatings); fall back to a query
            // only if this card was rendered without them.
            $reviewCount = $product->reviews_count ?? $product->approvedReviews()->count();
            $reviewAvg = (float) ($product->reviews_avg
                ?? ($reviewCount ? $product->approvedReviews()->avg('rating') : 0));
        @endphp
        @if($reviewCount > 0)
            <div class="product-rating" aria-label="Rated {{ number_format($reviewAvg, 1) }} out of 5 from {{ $reviewCount }} reviews">
                <span class="product-stars" style="--rating: {{ $reviewAvg }};" aria-hidden="true">★★★★★</span>
                <span class="product-rating-count">({{ $reviewCount }})</span>
            </div>
        @endif
    </div>
</a>
