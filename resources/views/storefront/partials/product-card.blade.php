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
                <span class="price-sale">LKR {{ number_format($product->sale_price, 2) }}</span>
                <span class="price-original">LKR {{ number_format($product->price, 2) }}</span>
            @else
                <span>LKR {{ number_format($product->price, 2) }}</span>
            @endif
        </div>
    </div>
</a>
