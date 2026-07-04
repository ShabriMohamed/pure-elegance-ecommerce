@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid md-grid-cols-4 gap-4" style="margin-bottom: var(--space-xl);">
    <div class="admin-card">
        <div style="color: var(--color-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-sm);">Total Revenue</div>
        <div style="font-size: 2rem; font-family: var(--font-serif); font-weight: 600; color: var(--color-gold);">LKR {{ number_format($stats['total_revenue'], 2) }}</div>
    </div>
    <div class="admin-card">
        <div style="color: var(--color-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-sm);">Total Orders</div>
        <div style="font-size: 2rem; font-family: var(--font-serif); font-weight: 600;">{{ number_format($stats['total_orders']) }}</div>
    </div>
    <div class="admin-card">
        <div style="color: var(--color-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-sm);">Customers</div>
        <div style="font-size: 2rem; font-family: var(--font-serif); font-weight: 600;">{{ number_format($stats['total_customers']) }}</div>
    </div>
    <div class="admin-card">
        <div style="color: var(--color-muted); font-size: 0.875rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-sm);">Products</div>
        <div style="font-size: 2rem; font-family: var(--font-serif); font-weight: 600;">{{ number_format($stats['total_products']) }}</div>
    </div>
</div>

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">Recent Orders</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem;">View All</a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td style="font-weight: 500;">{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>LKR {{ number_format($order->total, 2) }}</td>
                        <td>
                            @if($order->status === 'pending')
                                <span class="badge-warning">Pending</span>
                            @elseif($order->status === 'completed')
                                <span class="badge-success">Completed</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge-error">Cancelled</span>
                            @else
                                <span class="badge-success">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
