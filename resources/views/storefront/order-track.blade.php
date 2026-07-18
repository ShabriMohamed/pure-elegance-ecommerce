@extends('layouts.app')

@php
    $itemCount = (int) $order->items->sum('quantity');
    $names = $order->items->pluck('product_name')->filter()->take(2)->implode(', ');
    $more = $order->items->count() > 2 ? ' & more' : '';
    $storeName = site('site_name', 'Pure Elegance');
@endphp

@section('title', 'Order ' . $order->order_number . ' · ' . money($order->total))
@section('meta_description', $itemCount . ' ' . \Illuminate\Support\Str::plural('item', $itemCount) . ($names ? ' · ' . $names . $more : '') . ' · Tap to view your ' . $storeName . ' order.')
@section('og_image', $ogImage)

@section('content')
<div class="container" style="padding: var(--space-2xl) var(--space-md); max-width: 760px;">

    {{-- Header --}}
    <div style="text-align: center; margin-bottom: var(--space-xl);">
        <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 600; letter-spacing: 2px; color: var(--gold-text); text-transform: uppercase;">Order</div>
        <h1 class="font-h1" style="font-size: clamp(1.6rem, 4vw, 2.2rem);">#{{ $order->order_number }}</h1>
        <p style="color: var(--color-muted-text); font-size: 0.85rem;">Placed {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
    </div>

    {{-- Status tracker --}}
    @php
        $flow = ['whatsapp_sent' => 'Received', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered'];
        $terminal = in_array($order->status, ['cancelled', 'refunded'], true);
        $order_index = array_search($order->status, array_keys($flow), true);
    @endphp
    @if($terminal)
        <div class="alert alert-error" style="justify-content: center; margin-bottom: var(--space-xl);">
            <span class="material-symbols-outlined">info</span> This order is {{ $order->status_label }}.
        </div>
    @else
        <div style="display: flex; justify-content: space-between; gap: 4px; margin-bottom: var(--space-2xl); position: relative;">
            @foreach($flow as $key => $label)
                @php $done = $order_index !== false && array_search($key, array_keys($flow), true) <= $order_index; @endphp
                <div style="flex: 1; text-align: center;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center;
                        background: {{ $done ? 'var(--gradient-black-gold)' : 'var(--color-soft-gray)' }}; color: {{ $done ? '#fff' : 'var(--color-muted-text)' }};">
                        <span class="material-symbols-outlined" style="font-size: 1rem;">{{ $done ? 'check' : 'radio_button_unchecked' }}</span>
                    </div>
                    <div style="font-size: 0.62rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: {{ $done ? 'var(--color-primary-text)' : 'var(--color-muted-text)' }};">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Items --}}
    <div class="order-summary-card" style="position: static; margin-bottom: var(--space-lg);">
        <h2 class="order-summary-title">Your Items</h2>
        @foreach($order->items as $item)
            <div style="display: flex; gap: var(--space-md); align-items: center; padding: var(--space-sm) 0; border-bottom: 1px solid var(--color-border);">
                <div style="width: 56px; aspect-ratio: 4/5; background: var(--color-soft-gray); border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0;">
                    @if($item->product)
                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;">
                    @endif
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 0.9rem;">{{ $item->product_name }}</div>
                    @if($item->variant_info)<div style="font-size: 0.75rem; color: var(--color-muted-text);">{{ $item->variant_info }}</div>@endif
                    <div style="font-size: 0.78rem; color: var(--color-muted-text);">Qty {{ $item->quantity }} × {{ money($item->unit_price) }}</div>
                </div>
                <div style="font-weight: 600; font-size: 0.9rem;">{{ money($item->total_price) }}</div>
            </div>
        @endforeach

        <div class="order-summary-row" style="margin-top: var(--space-md);"><span>Subtotal</span><span>{{ money($order->subtotal) }}</span></div>
        @if($order->discount_amount > 0)
            <div class="order-summary-row" style="color: var(--color-error);"><span>Discount</span><span>- {{ money($order->discount_amount) }}</span></div>
        @endif
        <div class="order-summary-row"><span>Delivery</span><span>{{ $order->delivery_fee > 0 ? money($order->delivery_fee) : 'FREE' }}</span></div>
        <div class="order-summary-total">
            <span class="order-summary-total-label">Total</span>
            <span class="order-summary-total-value">{{ money($order->total) }}</span>
        </div>
    </div>

    {{-- Delivery details --}}
    <div class="card" style="padding: var(--space-lg); border: 1px solid var(--color-border); margin-bottom: var(--space-lg);">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-sm);">Delivery Details</h3>
        <div style="font-size: 0.85rem; color: var(--color-paragraph-text); line-height: 1.6;">
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->delivery_address }}<br>
            {{ trim(implode(', ', array_filter([$order->city, $order->postal_code]))) }}<br>
            {{ $order->customer_phone }}
        </div>
    </div>

    @if($contactUrl)
        <div style="text-align: center;">
            <a href="{{ $contactUrl }}" class="btn btn-gold" style="padding: 14px 28px;">
                <span class="material-symbols-outlined" style="font-size: 1.15rem;">chat</span>
                Contact us on WhatsApp
            </a>
        </div>
    @endif

    <div style="text-align: center; margin-top: var(--space-xl);">
        <a href="{{ route('home') }}" class="section-link">Continue Shopping <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_forward</span></a>
    </div>
</div>
@endsection
