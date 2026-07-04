@extends('storefront.account.layout')

@section('account_content')
<h1 style="font-size: 2rem; margin-bottom: var(--space-xl); font-family: var(--font-serif);">Account Dashboard</h1>

<div class="grid md-grid-cols-2 gap-4" style="margin-bottom: var(--space-2xl);">
    <div class="card" style="padding: var(--space-lg); border: none; background: var(--color-warm-beige);">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); color: var(--color-dark-charcoal); display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-outlined" style="color: var(--color-gold);">person</span>
            Profile Summary
        </h3>
        <div style="font-size: 0.875rem; color: var(--color-muted-text);">
            <div><strong>Name:</strong> {{ auth()->user()->name }}</div>
            <div><strong>Email:</strong> {{ auth()->user()->email }}</div>
            <div><strong>Member Since:</strong> {{ auth()->user()->created_at->format('M Y') }}</div>
        </div>
        <div style="margin-top: var(--space-md);">
            <a href="{{ route('account.profile') }}" style="color: var(--color-gold); font-size: 0.875rem; font-weight: 500;">Edit Profile &rarr;</a>
        </div>
    </div>

    <div class="card" style="padding: var(--space-lg); border: none; background: var(--color-warm-beige);">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); color: var(--color-dark-charcoal); display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-outlined" style="color: var(--color-gold);">local_shipping</span>
            Default Address
        </h3>
        <div style="font-size: 0.875rem; color: var(--color-muted-text);">
            @php
                $lastOrder = auth()->user()->orders()->latest()->first();
            @endphp
            @if($lastOrder)
                <div>{{ $lastOrder->delivery_address }}</div>
                <div>{{ $lastOrder->city }}</div>
                @if($lastOrder->postal_code)
                    <div>{{ $lastOrder->postal_code }}</div>
                @endif
            @else
                <div style="font-style: italic;">No address saved yet. It will be saved on your first order.</div>
            @endif
        </div>
    </div>
</div>

<h2 style="font-size: 1.5rem; margin-bottom: var(--space-lg); font-family: var(--font-sans); display: flex; justify-content: space-between; align-items: center;">
    Recent Orders
    <a href="{{ route('account.orders') }}" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">View All</a>
</h2>

@if($recentOrders->isEmpty())
    <div style="text-align: center; padding: var(--space-xl) 0; background: var(--color-warm-beige); border-radius: var(--radius-sm);">
        <span class="material-symbols-outlined" style="font-size: 3rem; color: var(--color-muted-text); margin-bottom: var(--space-sm);">shopping_bag</span>
        <p class="text-muted">You haven't placed any orders yet.</p>
        <a href="{{ route('categories') }}" class="btn btn-primary" style="margin-top: var(--space-md);">Start Shopping</a>
    </div>
@else
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--color-border); color: var(--color-muted-text); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
                    <th style="padding: var(--space-sm); text-align: left;">Order #</th>
                    <th style="padding: var(--space-sm); text-align: left;">Date</th>
                    <th style="padding: var(--space-sm); text-align: left;">Status</th>
                    <th style="padding: var(--space-sm); text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr style="border-bottom: 1px solid var(--color-border);">
                        <td style="padding: var(--space-md) var(--space-sm); font-weight: 500;">
                            <a href="{{ route('account.orders.show', $order) }}">{{ $order->order_number }}</a>
                        </td>
                        <td style="padding: var(--space-md) var(--space-sm); color: var(--color-muted-text); font-size: 0.875rem;">{{ $order->created_at->format('M d, Y') }}</td>
                        <td style="padding: var(--space-md) var(--space-sm);">
                            <span style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; padding: 0.2rem 0.5rem; border-radius: 2px; 
                                {{ $order->status === 'completed' || $order->status === 'delivered' ? 'background: #E8F5E9; color: var(--color-success);' : 
                                  ($order->status === 'cancelled' ? 'background: #FFEBEE; color: var(--color-error);' : 'background: #FFF3E0; color: #E65100;') }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td style="padding: var(--space-md) var(--space-sm); text-align: right; font-weight: 500;">LKR {{ number_format($order->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
