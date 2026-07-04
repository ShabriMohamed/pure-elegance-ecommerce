@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div style="background-color: var(--color-off-white); padding-bottom: var(--space-2xl); min-height: 80vh;">
    
    <!-- Header -->
    <div style="background: var(--color-pure-white); padding: var(--space-xl) 0; border-bottom: 1px solid var(--color-border); margin-bottom: var(--space-xl);">
        <div class="container">
            <h1 class="section-title" style="justify-content: center; font-size: 2rem;">Secure Checkout</h1>
        </div>
    </div>

    <div class="container">
        <div class="cart-layout">
            
            <!-- Left Column: Forms -->
            <div>
                <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    
                    <!-- Customer Information -->
                    <div style="background: var(--color-pure-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); padding: var(--space-xl); margin-bottom: var(--space-lg);">
                        <h2 class="font-h2" style="font-size: 1.25rem; margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm); color: var(--color-rich-black);">
                            <span class="material-symbols-outlined" style="color: var(--color-premium-gold);">person</span>
                            Contact Information
                        </h2>

                        <div class="grid md-grid-cols-2">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                @error('name')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- Delivery Details -->
                    <div style="background: var(--color-pure-white); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); padding: var(--space-xl);">
                        <h2 class="font-h2" style="font-size: 1.25rem; margin-bottom: var(--space-lg); display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm); color: var(--color-rich-black);">
                            <span class="material-symbols-outlined" style="color: var(--color-premium-gold);">local_shipping</span>
                            Delivery Details
                        </h2>

                        <div class="form-group">
                            <label for="address" class="form-label">Street Address *</label>
                            <input type="text" id="address" name="address" class="form-control" value="{{ old('address', auth()->user()->address_line1 ?? '') }}" required>
                            @error('address')<div class="form-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="grid md-grid-cols-2">
                            <div class="form-group">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" id="city" name="city" class="form-control" value="{{ old('city', auth()->user()->city ?? '') }}" required>
                                @error('city')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" class="form-control" value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}">
                                @error('postal_code')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div>
                <!-- Promo Code Form -->
                <div class="order-summary-card" style="margin-bottom: var(--space-lg); position: static;">
                    <h3 class="font-h3" style="font-size: 1rem; margin-bottom: var(--space-md); color: var(--color-rich-black);">Apply Promo Code</h3>
                    @if(session('applied_promo'))
                        <div class="alert alert-success">
                            <span style="display: flex; align-items: center; gap: 0.5rem;"><span class="material-symbols-outlined">sell</span> Code Applied: {{ session('applied_promo')['code'] }}</span>
                        </div>
                    @else
                        <form method="POST" action="{{ route('checkout.apply-promo') }}" style="display: flex; gap: 0.5rem;">
                            @csrf
                            <input type="text" name="promo_code" class="form-control" placeholder="Enter code" style="text-transform: uppercase;" required>
                            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Apply</button>
                        </form>
                    @endif
                </div>

                <!-- Summary -->
                <div class="order-summary-card">
                    <h2 class="order-summary-title">Order Summary</h2>
                    
                    <div style="margin-bottom: var(--space-lg); max-height: 300px; overflow-y: auto; padding-right: 0.5rem;">
                        @foreach($cart->items as $item)
                            <div style="display: flex; gap: var(--space-md); margin-bottom: var(--space-md); font-family: var(--font-sans);">
                                <div style="width: 60px; aspect-ratio: 4/5; background: var(--color-soft-gray); border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0;">
                                    <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 0.85rem; font-weight: 500; color: var(--color-rich-black); line-height: 1.3; margin-bottom: 2px;">{{ $item->product->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--color-medium-gray);">Qty: {{ $item->quantity }}</div>
                                </div>
                                <div style="font-weight: 600; color: var(--color-rich-black); font-size: 0.9rem;">
                                    LKR {{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @php
                        $subtotal = $cart->subtotal;
                        $discount = session('applied_promo')['discount'] ?? 0;
                        $deliveryFee = 500.00;
                        $total = ($subtotal - $discount) + $deliveryFee;
                    @endphp

                    <div class="order-summary-row">
                        <span>Subtotal</span>
                        <span style="font-weight: 500; color: var(--color-rich-black);">LKR {{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    @if($discount > 0)
                        <div class="order-summary-row" style="color: var(--color-error);">
                            <span>Discount</span>
                            <span style="font-weight: 600;">- LKR {{ number_format($discount, 2) }}</span>
                        </div>
                    @endif
                    
                    <div class="order-summary-row">
                        <span>Delivery Fee</span>
                        <span style="font-weight: 500; color: var(--color-rich-black);">LKR {{ number_format($deliveryFee, 2) }}</span>
                    </div>
                    
                    <div class="order-summary-total">
                        <span class="order-summary-total-label">Total</span>
                        <span class="order-summary-total-value">LKR {{ number_format($total, 2) }}</span>
                    </div>

                    <button type="button" class="btn btn-primary btn-block" style="padding: 1.25rem; font-size: 0.9rem; letter-spacing: 1.5px; margin-bottom: var(--space-sm);" onclick="document.getElementById('checkout-form').submit();">
                        COMPLETE ORDER
                        <span class="material-symbols-outlined" style="font-size: 1.2rem;">arrow_forward</span>
                    </button>
                    
                    <div style="text-align: center; font-family: var(--font-sans); font-size: 0.7rem; color: var(--color-medium-gray);">
                        By completing your order, you agree to our Terms of Service and Privacy Policy. Payment will be processed via WhatsApp.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
