@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">All Orders</h2>
        
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: var(--space-sm);">
            <select name="status" class="form-control" style="width: auto; padding: 0.25rem 0.5rem;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(\App\Models\Order::statuses() as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
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
                        <td>{{ money($order->total) }}</td>
                        <td>
                            @if(in_array($order->status, ['pending', 'whatsapp_sent']))
                                <span class="badge-warning">{{ $order->status_label }}</span>
                            @elseif(in_array($order->status, ['cancelled', 'refunded']))
                                <span class="badge-error">{{ $order->status_label }}</span>
                            @else
                                <span class="badge-success">{{ $order->status_label }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No orders found matching the criteria.</td>
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
