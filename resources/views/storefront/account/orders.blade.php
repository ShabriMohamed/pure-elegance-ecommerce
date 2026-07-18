@extends('storefront.account.layout')

@section('account_content')
<h1 style="font-size: 1.8rem; margin-bottom: var(--space-xl); font-family: var(--font-serif);">My Orders</h1>

@if($orders->count() > 0)
    <div style="display: flex; flex-direction: column; gap: var(--space-md);">
        @foreach($orders as $order)
        <div class="card" style="padding: var(--space-lg);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-md); flex-wrap: wrap; gap: var(--space-sm);">
                <div>
                    <div style="font-size: 0.9rem; font-weight: 600; color: var(--color-primary-text);">
                        Order #{{ $order->order_number }}
                    </div>
                    <div style="font-size: 0.75rem; color: var(--color-muted-text); margin-top: 2px;">
                        {{ $order->created_at->format('M d, Y') }}
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: var(--space-md);">
                    <span style="
                        display: inline-block;
                        padding: 4px 12px;
                        border-radius: var(--radius-full);
                        font-size: 0.7rem;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        @if($order->status === 'delivered')
                            background: #E8F5E9; color: var(--color-success);
                        @elseif($order->status === 'cancelled')
                            background: #FFEBEE; color: var(--color-error);
                        @elseif($order->status === 'shipped')
                            background: #E3F2FD; color: #1565C0;
                        @else
                            background: var(--color-warm-beige); color: var(--color-coffee-brown);
                        @endif
                    ">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            @if($order->items->count() > 0)
            <div style="display: flex; gap: var(--space-sm); overflow-x: auto; padding-bottom: var(--space-sm); scrollbar-width: none;">
                @foreach($order->items->take(4) as $item)
                <div style="flex-shrink: 0; width: 50px; height: 50px; border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--color-light-gray); background: var(--color-soft-gray);">
                    @if($item->product)
                        <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @endif
                </div>
                @endforeach
                @if($order->items->count() > 4)
                    <div style="flex-shrink: 0; width: 50px; height: 50px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; background: var(--color-soft-gray); font-size: 0.7rem; color: var(--color-muted-text); font-weight: 600;">
                        +{{ $order->items->count() - 4 }}
                    </div>
                @endif
            </div>
            @endif

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: var(--space-md); padding-top: var(--space-md); border-top: 1px solid var(--color-light-gray);">
                <div style="font-size: 0.9rem; font-weight: 600; color: var(--color-primary-text);">
                    {{ money($order->total) }}
                </div>
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline" style="padding: 6px 16px; font-size: 0.7rem;">
                    VIEW ORDER
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($orders->hasPages())
        <div style="margin-top: var(--space-xl);">
            {{ $orders->links() }}
        </div>
    @endif
@else
    <div style="text-align: center; padding: var(--space-3xl) 0;">
        <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-light-gray); display: block; margin-bottom: var(--space-md);">receipt_long</span>
        <h3 style="font-family: var(--font-serif); font-size: 1.3rem; color: var(--color-primary-text); margin-bottom: var(--space-sm);">No orders yet</h3>
        <p style="color: var(--color-muted-text); font-size: 0.9rem; margin-bottom: var(--space-lg);">Start shopping to see your orders here.</p>
        <a href="{{ route('categories') }}" class="btn btn-primary">SHOP NOW</a>
    </div>
@endif
@endsection
