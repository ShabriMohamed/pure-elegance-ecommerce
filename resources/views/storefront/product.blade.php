@extends('layouts.app')

@section('title', $product->name)

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
                    <span style="color: var(--color-error);">LKR {{ number_format($product->sale_price, 2) }}</span>
                    <span style="text-decoration: line-through; color: var(--color-muted-text); font-size: 1rem; font-weight: 400;">LKR {{ number_format($product->price, 2) }}</span>
                @else
                    <span style="color: var(--color-primary-text);">LKR {{ number_format($product->price, 2) }}</span>
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
                                        @if($variant->price_adjustment > 0) (+LKR {{ number_format($variant->price_adjustment, 2) }}) @endif
                                        {{ $variant->stock_quantity <= 0 ? ' (Out of Stock)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div style="display: flex; gap: var(--space-md);">
                        <div style="width: 100px;">
                            <input type="number" name="quantity" value="1" min="1" max="10" class="form-control" style="text-align: center; height: 48px; font-weight: 600; font-size: 1rem; background: var(--color-soft-gray); border: 1px solid var(--color-border);">
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
                    <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.4rem;">local_shipping</span>
                    <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">Free Delivery<br><span style="color: var(--color-muted-text); font-weight: 400;">Over LKR 10,000</span></div>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.4rem;">verified</span>
                    <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">100% Authentic<br><span style="color: var(--color-muted-text); font-weight: 400;">Genuine Products</span></div>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.4rem;">360</span>
                    <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">Easy Returns<br><span style="color: var(--color-muted-text); font-weight: 400;">7 Day Policy</span></div>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-md); background: var(--color-soft-gray); border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="color: var(--color-premium-gold); font-size: 1.4rem;">lock</span>
                    <div class="font-body" style="font-size: 0.7rem; font-weight: 500; color: var(--color-secondary-text);">Secure Payment<br><span style="color: var(--color-muted-text); font-weight: 400;">Safe Checkout</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
