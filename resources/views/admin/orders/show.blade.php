@extends('layouts.admin')

@section('title', 'Order Details')

@section('breadcrumb')
<nav style="font-size: 0.8rem; color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--color-muted);">Dashboard</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <a href="{{ route('admin.orders.index') }}" style="color: var(--color-muted);">Orders</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <span>#{{ $order->order_number }}</span>
</nav>
@endsection

@push('styles')
<style>
.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}
@media (min-width: 992px) {
    .order-detail-grid { grid-template-columns: 2fr 1fr; }
}
</style>
@endpush

@section('content')
<div style="margin-bottom: var(--space-lg);">
    <a href="{{ route('admin.orders.index') }}" style="color: var(--color-muted); font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_back</span> Back to Orders
    </a>
</div>

<div class="order-detail-grid">
    
    <!-- Order Items & Details -->
    <div>
        <div class="admin-card" style="margin-bottom: var(--space-xl);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
                <div>
                    <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">Order #{{ $order->order_number }}</h2>
                    <div style="font-size: 0.875rem; color: var(--color-muted); margin-top: 0.25rem;">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</div>
                </div>
            </div>

            {{-- Scroll container so the line-item table doesn't overflow the page on mobile --}}
            <div style="overflow-x: auto; margin-bottom: var(--space-lg);">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight: 500;">{{ $item->product_name }}</div>
                                @if($item->variant_info)
                                    <div style="font-size: 0.75rem; color: var(--color-muted);">Variant: {{ $item->variant_info }}</div>
                                @endif
                            </td>
                            <td>{{ money($item->unit_price) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td style="text-align: right; font-weight: 500;">{{ money($item->total_price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end; padding-top: var(--space-md); border-top: 1px solid var(--color-border);">
                <div style="display: flex; justify-content: space-between; width: min(250px, 100%); color: var(--color-muted);">
                    <span>Subtotal</span>
                    <span>{{ money($order->subtotal) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; width: min(250px, 100%); color: var(--color-muted);">
                    <span>Discount</span>
                    <span>- {{ money($order->discount_amount) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; width: min(250px, 100%); color: var(--color-muted);">
                    <span>Delivery</span>
                    <span>{{ money($order->delivery_fee) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; width: min(250px, 100%); font-weight: 600; font-size: 1.125rem; margin-top: 0.5rem; padding-top: 0.5rem; border-top: 1px solid var(--color-border);">
                    <span>Total</span>
                    <span>{{ money($order->total) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status & Customer Info -->
    <div>
        <!-- Status Management -->
        <div class="admin-card" style="margin-bottom: var(--space-xl);">
            <h3 style="font-size: 1rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Manage Status</h3>
            
            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                @csrf
                @method('PATCH')
                
                @php $allowedStatuses = array_merge([$order->status], $order->nextStatuses()); @endphp
                <div class="form-group">
                    <label class="form-label">Order Status</label>
                    <select name="status" class="form-control" {{ empty($order->nextStatuses()) ? 'disabled' : '' }}>
                        @foreach(\App\Models\Order::statuses() as $value => $label)
                            @if(in_array($value, $allowedStatuses, true))
                                <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                    <p style="font-size: 0.72rem; color: var(--color-muted); margin-top: 6px;">
                        Only valid next transitions are shown.
                    </p>
                </div>

                @if(empty($order->nextStatuses()))
                    <p style="font-size: 0.8rem; color: var(--color-muted);">This order is in a final state ({{ $order->status_label }}).</p>
                @else
                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                @endif
            </form>
        </div>

        <!-- Customer Details -->
        <div class="admin-card">
            <h3 style="font-size: 1rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Customer Information</h3>
            
            <div style="margin-bottom: var(--space-md);">
                <div style="font-weight: 500; margin-bottom: 0.25rem;">{{ $order->customer_name }}</div>
                <div style="color: var(--color-muted); font-size: 0.875rem;">
                    <div><span class="material-symbols-outlined" style="font-size: 1rem; vertical-align: bottom;">mail</span> <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></div>
                    <div style="margin-top: 0.25rem;"><span class="material-symbols-outlined" style="font-size: 1rem; vertical-align: bottom;">call</span> {{ $order->customer_phone }}</div>
                </div>
            </div>

            <h4 style="font-size: 0.875rem; margin-bottom: 0.5rem; margin-top: var(--space-md); color: var(--color-charcoal);">Shipping Address</h4>
            <div style="color: var(--color-muted); font-size: 0.875rem; line-height: 1.5;">
                {{ $order->delivery_address }}<br>
                {{ $order->city }}
                @if($order->postal_code)
                    , {{ $order->postal_code }}
                @endif
            </div>

            @if($order->notes)
                <h4 style="font-size: 0.875rem; margin-bottom: 0.5rem; margin-top: var(--space-md); color: var(--color-charcoal);">Customer Notes</h4>
                <div style="background: var(--color-cream); padding: var(--space-sm); border-radius: var(--radius-sm); color: var(--color-muted); font-size: 0.875rem; font-style: italic;">
                    "{{ $order->notes }}"
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
