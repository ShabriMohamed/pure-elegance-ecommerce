@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="container" style="padding: var(--space-2xl) var(--space-md); max-width: 640px;">
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <span class="material-symbols-outlined" style="font-size: 3.5rem; color: var(--color-success);">check_circle</span>
        <h1 class="font-h1" style="font-size: clamp(1.6rem, 4vw, 2.2rem); margin-top: var(--space-sm);">Thank you for your order!</h1>
        <p style="color: var(--color-muted-text); margin-top: var(--space-xs);">
            Your order <strong>#{{ $order->order_number }}</strong> has been placed.
            Keep this number for your records.
        </p>
    </div>

    @if($whatsappReady)
        {{-- WhatsApp handoff. The anchor navigates to wa.me via its href (allowed by
             CSP), while app.js records the "opened" state with a same-origin fetch.
             data-wa-auto triggers the timed auto-open on first load. --}}
        <div data-surface="dark" style="background: var(--color-rich-black); border: 1px solid rgba(200,155,60,0.3); border-radius: var(--radius-md); padding: var(--space-xl); text-align: center; margin-bottom: var(--space-xl);">
            <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 600; letter-spacing: 2px; color: var(--gold-text); text-transform: uppercase; margin-bottom: var(--space-sm);">
                Final Step
            </div>
            <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-bottom: var(--space-lg); line-height: 1.6;">
                @if($autoOpenWhatsapp)
                    Opening WhatsApp so you can send us your order&hellip;<br>If it doesn't open, tap the button below.
                @else
                    Tap below to open WhatsApp and send us your order.
                @endif
            </p>
            <a href="{{ $whatsappUrl }}" class="btn btn-gold" style="padding: 16px 32px;"
               data-wa-open data-wa-mark="{{ route('checkout.whatsapp', $order, false) }}"
               @if($autoOpenWhatsapp) data-wa-auto @endif rel="noopener">
                <span class="material-symbols-outlined" style="font-size: 1.2rem;">chat</span>
                {{ $order->whatsapp_sent_at ? 'OPEN WHATSAPP AGAIN' : 'SEND ORDER ON WHATSAPP' }}
            </a>
            @if($order->whatsapp_sent_at)
                <div style="margin-top: var(--space-md); font-size: 0.75rem; color: rgba(255,255,255,0.55);">
                    WhatsApp was opened for this order {{ $order->whatsapp_sent_at->diffForHumans() }}.<br>
                    If you didn't press <strong>Send</strong> in WhatsApp, tap the button again.
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-success" style="margin-bottom: var(--space-xl); justify-content: center;">
            <span class="material-symbols-outlined">support_agent</span>
            Our team will contact you shortly on {{ $order->customer_phone }} to confirm delivery.
        </div>
    @endif

    <div class="order-summary-card" style="position: static;">
        <h2 class="order-summary-title">Order Summary</h2>
        @foreach($order->items as $item)
            <div class="order-summary-row">
                <span>{{ $item->quantity }}× {{ $item->product_name }}@if($item->variant_info) <span class="text-muted">({{ $item->variant_info }})</span>@endif</span>
                <span style="color: var(--color-rich-black); font-weight: 500;">{{ money($item->total_price) }}</span>
            </div>
        @endforeach
        <div class="order-summary-row"><span>Subtotal</span><span>{{ money($order->subtotal) }}</span></div>
        @if($order->discount_amount > 0)
            <div class="order-summary-row" style="color: var(--color-error);"><span>Discount</span><span>- {{ money($order->discount_amount) }}</span></div>
        @endif
        <div class="order-summary-row"><span>Delivery</span><span>{{ $order->delivery_fee > 0 ? money($order->delivery_fee) : 'FREE' }}</span></div>
        <div class="order-summary-total">
            <span class="order-summary-total-label">Total</span>
            <span class="order-summary-total-value">{{ money($order->total) }}</span>
        </div>
    </div>

    <div style="display: flex; gap: var(--space-md); margin-top: var(--space-xl); flex-wrap: wrap; justify-content: center;">
        <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
        @auth
            <a href="{{ route('account.orders') }}" class="btn btn-outline">View My Orders</a>
        @endauth
    </div>
</div>
@endsection
