@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div style="margin-bottom: var(--space-lg);">
    <a href="{{ route('admin.customers.index') }}" style="color: var(--color-muted); font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_back</span> Back to Customers
    </a>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: var(--space-xl);">
    {{-- Customer Profile Card --}}
    <div class="admin-card">
        <div style="text-align: center; margin-bottom: var(--space-lg);">
            <div style="width: 72px; height: 72px; border-radius: var(--radius-full); background: linear-gradient(135deg, var(--color-gold), #D4A03C); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem; color: white; margin: 0 auto var(--space-md);">
                {{ strtoupper(substr($customer->first_name ?? $customer->name, 0, 1)) }}{{ strtoupper(substr($customer->last_name ?? '', 0, 1)) }}
            </div>
            <h2 style="font-size: 1.25rem; font-family: var(--font-serif); font-weight: 600;">{{ $customer->name }}</h2>
            <div style="margin-top: var(--space-xs);">
                @if($customer->is_active)
                    <span class="badge-success">Active</span>
                @else
                    <span class="badge-error">Inactive</span>
                @endif
            </div>
        </div>

        <div style="border-top: 1px solid var(--color-border); padding-top: var(--space-md);">
            <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 0.875rem;">
                <span class="material-symbols-outlined" style="font-size: 1rem; color: var(--color-muted);">mail</span>
                <a href="mailto:{{ $customer->email }}" style="color: var(--color-charcoal);">{{ $customer->email }}</a>
            </div>
            <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 0.875rem;">
                <span class="material-symbols-outlined" style="font-size: 1rem; color: var(--color-muted);">phone</span>
                <span>{{ $customer->phone ?? 'Not provided' }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 0.875rem;">
                <span class="material-symbols-outlined" style="font-size: 1rem; color: var(--color-muted);">calendar_today</span>
                <span>Joined {{ $customer->created_at->format('M d, Y') }}</span>
            </div>
            @if($customer->last_login_at)
            <div style="display: flex; align-items: center; gap: var(--space-sm); font-size: 0.875rem;">
                <span class="material-symbols-outlined" style="font-size: 1rem; color: var(--color-muted);">login</span>
                <span>Last login {{ $customer->last_login_at->diffForHumans() }}</span>
            </div>
            @endif
        </div>

        <div style="margin-top: var(--space-lg); border-top: 1px solid var(--color-border); padding-top: var(--space-md);">
            <form method="POST" action="{{ route('admin.customers.toggle-active', $customer) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $customer->is_active ? 'btn-outline' : 'btn-primary' }} btn-block" style="font-size: 0.85rem;">
                    {{ $customer->is_active ? 'Deactivate Account' : 'Activate Account' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="admin-card">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Recent Orders</h3>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $order)
                        <tr>
                            <td style="font-weight: 500;">{{ $order->order_number }}</td>
                            <td style="font-size: 0.85rem;">{{ $order->created_at->format('M d, Y') }}</td>
                            <td>LKR {{ number_format($order->total, 2) }}</td>
                            <td>
                                @php
                                    $badgeClass = match($order->status) {
                                        'pending' => 'badge-warning',
                                        'cancelled', 'refunded' => 'badge-error',
                                        default => 'badge-success',
                                    };
                                @endphp
                                <span class="{{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">This customer has no orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
