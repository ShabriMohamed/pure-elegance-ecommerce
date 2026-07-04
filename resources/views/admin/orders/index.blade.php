@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">All Orders</h2>
        
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: var(--space-sm);">
            <select name="status" class="form-control" style="width: auto; padding: 0.25rem 0.5rem;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="font-weight: 500;">{{ $order->order_number }}</td>
                        <td>
                            <div>{{ $order->customer_name }}</div>
                            <div style="font-size: 0.75rem; color: var(--color-muted);">{{ $order->customer_email }}</div>
                        </td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td>LKR {{ number_format($order->total, 2) }}</td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge-success">Paid</span>
                            @elseif($order->payment_status === 'unpaid')
                                <span class="badge-error">Unpaid</span>
                            @else
                                <span class="badge-warning">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($order->status === 'pending')
                                <span class="badge-warning">Pending</span>
                            @elseif($order->status === 'completed' || $order->status === 'delivered')
                                <span class="badge-success">Completed</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge-error">Cancelled</span>
                            @else
                                <span class="badge-success">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No orders found matching the criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: var(--space-lg);">
        {{ $orders->links() }}
    </div>
</div>
@endsection
