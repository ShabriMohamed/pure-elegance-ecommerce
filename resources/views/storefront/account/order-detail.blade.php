@extends('storefront.account.layout')

@section('account_content')
<div style="margin-bottom: var(--space-lg);">
    <a href="{{ route('account.orders') }}" style="color: var(--color-muted); font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_back</span> Back to Orders
    </a>
</div>

<h1 style="font-size: 2rem; margin-bottom: var(--space-xl); font-family: var(--font-serif);">Order Details</h1>

<div class="card" style="border: none; box-shadow: var(--shadow-sm); margin-bottom: var(--space-xl);">
    <div style="background: var(--color-cream); padding: var(--space-lg); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border);">
        <div>
            <h2 style="font-size: 1.25rem; margin-bottom: 0.25rem;">Order #{{ $order->order_number }}</h2>
            <div style="font-size: 0.875rem; color: var(--color-muted);">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</div>
        </div>
        <div>
            <span style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; padding: 0.4rem 1rem; border-radius: var(--radius-full); 
                {{ $order->status === 'completed' || $order->status === 'delivered' ? 'background: #E8F5E9; color: var(--color-success);' : 
                  ($order->status === 'cancelled' ? 'background: #FFEBEE; color: var(--color-error);' : 'background: #FFF3E0; color: #E65100;') }}">
                {{ $order->status }}
            </span>
        </div>
    </div>

    <div style="padding: var(--space-xl);">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Items in your order</h3>
        
        @foreach($order->items as $item)
            <div style="display: flex; gap: var(--space-lg); margin-bottom: var(--space-lg); padding-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border);">
                @if($item->product)
                    <div style="width: 80px; height: 100px; background: var(--color-cream); border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0;">
                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif
                <div style="flex-grow: 1;">
                    <div style="display: flex; justify-content: space-between;">
                        <a href="{{ $item->product ? route('product.show', $item->product->slug) : '#' }}" style="font-weight: 500; font-size: 1.125rem;">{{ $item->product_name }}</a>
                        <div style="font-weight: 500;">LKR {{ number_format($item->total_price, 2) }}</div>
                    </div>
                    <div style="font-size: 0.875rem; color: var(--color-muted); margin-top: 0.25rem;">
                        @if($item->variant_info)
                            Variant: {{ $item->variant_info }} <br>
                        @endif
                        Qty: {{ $item->quantity }} <br>
                        Price: LKR {{ number_format($item->unit_price, 2) }} each
                    </div>
                </div>
            </div>
        @endforeach

        <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end; margin-top: var(--space-lg);">
            <div style="display: flex; justify-content: space-between; width: 300px; color: var(--color-muted);">
                <span>Subtotal</span>
                <span>LKR {{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
                <div style="display: flex; justify-content: space-between; width: 300px; color: var(--color-error);">
                    <span>Discount</span>
                    <span>- LKR {{ number_format($order->discount_amount, 2) }}</span>
                </div>
            @endif
            <div style="display: flex; justify-content: space-between; width: 300px; color: var(--color-muted);">
                <span>Delivery Fee</span>
                <span>LKR {{ number_format($order->delivery_fee, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; width: 300px; font-weight: 600; font-size: 1.25rem; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid var(--color-obsidian);">
                <span>Total</span>
                <span>LKR {{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="grid md-grid-cols-2 gap-4">
    <div class="card" style="padding: var(--space-lg); border: none; background: var(--color-cream);">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Delivery Address</h3>
        <div style="font-size: 0.875rem; color: var(--color-charcoal); line-height: 1.6;">
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->delivery_address }}<br>
            {{ $order->city }}
            @if($order->postal_code)
                , {{ $order->postal_code }}
            @endif
            <br>
            {{ $order->country }}<br>
            <span style="color: var(--color-muted); margin-top: 0.5rem; display: block;">{{ $order->customer_phone }}</span>
        </div>
    </div>

    <div class="card" style="padding: var(--space-lg); border: none; background: var(--color-cream);">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Payment Information</h3>
        <div style="font-size: 0.875rem; color: var(--color-charcoal); line-height: 1.6;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                <span class="text-muted">Payment Method:</span>
                <span>Bank Transfer / Cash</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span class="text-muted">Order Status:</span>
                <span style="font-weight: 500; {{ in_array($order->status, ['cancelled', 'refunded']) ? 'color: var(--color-error);' : 'color: var(--color-success);' }}">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
