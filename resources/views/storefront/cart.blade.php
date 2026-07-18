@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div style="background-color: var(--color-off-white); padding-bottom: var(--space-2xl); min-height: 60vh;">
    
    <!-- Header -->
    <div style="background: var(--color-pure-white); padding: var(--space-xl) 0; border-bottom: 1px solid var(--color-border); margin-bottom: var(--space-xl);">
        <div class="container">
            <h1 class="section-title" style="justify-content: center; font-size: 2rem;">Your Shopping Cart</h1>
        </div>
    </div>

    <div class="container">
        @if($cart->items->isEmpty())
            <div style="text-align: center; padding: var(--space-2xl) 0; background: var(--color-pure-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                <div style="color: var(--gold-text); margin-bottom: var(--space-md);">
                    <span class="material-symbols-outlined" style="font-size: 4rem; opacity: 0.5;">shopping_cart</span>
                </div>
                <h2 class="section-title" style="justify-content: center; font-size: 1.5rem; margin-bottom: var(--space-sm);">Your cart is empty</h2>
                <p style="font-family: var(--font-sans); color: var(--color-medium-gray); margin-bottom: var(--space-lg);">Looks like you haven't added anything to your cart yet.</p>
                <a href="{{ route('categories') }}" class="btn btn-primary" style="padding: 1rem 3rem;">Continue Shopping</a>
            </div>
        @else
            <div class="cart-layout">
                
                <!-- Cart Items -->
                <div style="background: var(--color-pure-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); padding: var(--space-lg);">
                    <div style="border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm); margin-bottom: var(--space-lg); display: none;" class="desktop-only">
                        <div style="display: flex; font-family: var(--font-sans); font-size: 0.75rem; font-weight: 600; color: var(--color-medium-gray); text-transform: uppercase; letter-spacing: 1px;">
                            <div style="flex: 2;">Product</div>
                            <div style="flex: 1; text-align: center;">Quantity</div>
                            <div style="flex: 1; text-align: right;">Total</div>
                        </div>
                    </div>

                    @foreach($cart->items as $item)
                        <div class="cart-item-row">
                            <!-- Product Details -->
                            <a href="{{ route('product.show', $item->product->slug) }}" class="cart-item-image">
                                <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}">
                            </a>
                            <div class="cart-item-details">
                                <div class="cart-item-brand">{{ $item->product->brand ?? 'PURE ELEGANCE' }}</div>
                                <a href="{{ route('product.show', $item->product->slug) }}" class="cart-item-name" style="display: block; color: inherit;">
                                    {{ $item->product->name }}
                                </a>
                                @if($item->variant)
                                    <div class="cart-item-variant">
                                        Size: <strong>{{ $item->variant->size }}</strong>
                                        @if($item->variant->color) | Color: <strong>{{ $item->variant->color }}</strong> @endif
                                    </div>
                                @endif
                                <div class="cart-item-price">{{ money($item->price) }}</div>
                                
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cart-item-remove">
                                        <span class="material-symbols-outlined">delete_outline</span> Remove
                                    </button>
                                </form>
                            </div>

                            <!-- Quantity -->
                            <div class="cart-item-qty">
                                <form method="POST" action="{{ route('cart.update', $item->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input').stepDown(); this.form.submit();">
                                            <span class="material-symbols-outlined" style="font-size: 1rem;">remove</span>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" class="cart-qty-input" onchange="this.form.submit()">
                                        <button type="button" class="qty-btn" onclick="this.parentNode.querySelector('input').stepUp(); this.form.submit();">
                                            <span class="material-symbols-outlined" style="font-size: 1rem;">add</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Item Total -->
                            <div class="cart-item-total">
                                {{ money($item->price * $item->quantity) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div>
                    <div class="order-summary-card">
                        <h2 class="order-summary-title">Order Summary</h2>
                        
                        <div class="order-summary-row">
                            <span>Subtotal</span>
                            <span style="font-weight: 500;">{{ money($cart->subtotal) }}</span>
                        </div>
                        
                        <div class="order-summary-row">
                            <span>Delivery</span>
                            <span style="font-size: 0.85rem; color: var(--color-medium-gray);">Calculated at checkout</span>
                        </div>
                        
                        <div class="order-summary-total">
                            <span class="order-summary-total-label">Estimated Total</span>
                            <span class="order-summary-total-value">{{ money($cart->subtotal) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-block" style="padding: 1.2rem; font-size: 0.85rem; letter-spacing: 1.5px;">
                            PROCEED TO CHECKOUT
                            <span class="material-symbols-outlined" style="font-size: 1.1rem;">lock</span>
                        </a>

                        <!-- Trust Badges -->
                        <div class="trust-badges">
                            <div class="trust-badges-icons">
                                <span class="material-symbols-outlined">payments</span>
                                <span class="material-symbols-outlined">shield</span>
                                <span class="material-symbols-outlined">local_shipping</span>
                            </div>
                            <div class="trust-badges-text">
                                Secure Checkout. Easy Returns.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>
@endsection
