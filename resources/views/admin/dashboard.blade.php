@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- ── Pending Orders Alert ─────────────────────────────── --}}
@if($pendingOrdersCount > 0)
<div style="background: linear-gradient(135deg, #FFF3E0, #FFE0B2); border: 1px solid #FFB74D; border-radius: var(--radius-md); padding: var(--space-md) var(--space-lg); margin-bottom: var(--space-xl); display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: var(--space-md);">
        <span class="material-symbols-outlined" style="font-size: 1.5rem; color: #E65100;">notifications_active</span>
        <div>
            <div style="font-weight: 600; color: #E65100;">{{ $pendingOrdersCount }} Pending {{ Str::plural('Order', $pendingOrdersCount) }}</div>
            <div style="font-size: 0.8rem; color: #BF360C;">Requires your attention</div>
        </div>
    </div>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; background: #E65100;">
        Review Now
    </a>
</div>
@endif

{{-- ── Quick Actions Bar ────────────────────────────────── --}}
<div style="display: flex; gap: var(--space-md); margin-bottom: var(--space-xl); flex-wrap: wrap;">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.6rem 1.2rem; font-size: 0.8rem;">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">add</span> New Product
    </a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.6rem 1.2rem; font-size: 0.8rem;">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">list_alt</span> All Orders
    </a>
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.6rem 1.2rem; font-size: 0.8rem;">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">sell</span> New Promo
    </a>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.6rem 1.2rem; font-size: 0.8rem;">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">group</span> Customers
    </a>
</div>

{{-- ── Core KPI Cards ───────────────────────────────────── --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--space-lg); margin-bottom: var(--space-xl);">
    <div class="admin-card" style="border-left: 4px solid var(--color-gold);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-xs);">Total Revenue</div>
                <div style="font-size: 1.75rem; font-family: var(--font-serif); font-weight: 600; color: var(--color-gold);">{{ money($stats['total_revenue']) }}</div>
            </div>
            <span class="material-symbols-outlined" style="font-size: 2rem; color: var(--color-gold); opacity: 0.3;">payments</span>
        </div>
        <div style="margin-top: var(--space-sm); font-size: 0.75rem; color: var(--color-muted);">
            Today: {{ money($today['revenue']) }}
        </div>
    </div>

    <div class="admin-card" style="border-left: 4px solid #1565C0;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-xs);">Total Orders</div>
                <div style="font-size: 1.75rem; font-family: var(--font-serif); font-weight: 600;">{{ number_format($stats['total_orders']) }}</div>
            </div>
            <span class="material-symbols-outlined" style="font-size: 2rem; color: #1565C0; opacity: 0.3;">shopping_cart</span>
        </div>
        <div style="margin-top: var(--space-sm); font-size: 0.75rem; color: var(--color-muted);">
            Today: {{ $today['orders'] }} {{ Str::plural('order', $today['orders']) }}
        </div>
    </div>

    <div class="admin-card" style="border-left: 4px solid #2E7D32;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-xs);">Customers</div>
                <div style="font-size: 1.75rem; font-family: var(--font-serif); font-weight: 600;">{{ number_format($stats['total_customers']) }}</div>
            </div>
            <span class="material-symbols-outlined" style="font-size: 2rem; color: #2E7D32; opacity: 0.3;">group</span>
        </div>
        <div style="margin-top: var(--space-sm); font-size: 0.75rem; color: var(--color-muted);">
            New today: {{ $today['new_customers'] }}
        </div>
    </div>

    <div class="admin-card" style="border-left: 4px solid #6A1B9A;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="color: var(--color-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-xs);">Active Products</div>
                <div style="font-size: 1.75rem; font-family: var(--font-serif); font-weight: 600;">{{ number_format($stats['total_products']) }}</div>
            </div>
            <span class="material-symbols-outlined" style="font-size: 2rem; color: #6A1B9A; opacity: 0.3;">inventory_2</span>
        </div>
        <div style="margin-top: var(--space-sm); font-size: 0.75rem; color: {{ $lowStockProducts->count() > 0 ? '#C62828' : 'var(--color-muted)' }};">
            @if($lowStockProducts->count() > 0)
                <span class="material-symbols-outlined" style="font-size: 0.85rem; vertical-align: middle;">warning</span>
                {{ $lowStockProducts->count() }} low stock
            @else
                All stocked
            @endif
        </div>
    </div>
</div>

{{-- ── Order Status Breakdown + Monthly Revenue ─────────── --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-xl); margin-bottom: var(--space-xl);">
    {{-- Order Pipeline --}}
    <div class="admin-card">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Order Pipeline</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            @php
                $statusConfig = [
                    'pending' => ['label' => 'Pending', 'color' => '#E65100', 'bg' => '#FFF3E0', 'icon' => 'hourglass_top'],
                    'whatsapp_sent' => ['label' => 'WhatsApp Sent', 'color' => '#2E7D32', 'bg' => '#E8F5E9', 'icon' => 'chat'],
                    'confirmed' => ['label' => 'Confirmed', 'color' => '#1565C0', 'bg' => '#E3F2FD', 'icon' => 'check_circle'],
                    'processing' => ['label' => 'Processing', 'color' => '#6A1B9A', 'bg' => '#F3E5F5', 'icon' => 'sync'],
                    'shipped' => ['label' => 'Shipped', 'color' => '#00695C', 'bg' => '#E0F2F1', 'icon' => 'local_shipping'],
                    'delivered' => ['label' => 'Delivered', 'color' => '#2E7D32', 'bg' => '#E8F5E9', 'icon' => 'done_all'],
                    'cancelled' => ['label' => 'Cancelled', 'color' => '#C62828', 'bg' => '#FFEBEE', 'icon' => 'cancel'],
                    'refunded' => ['label' => 'Refunded', 'color' => '#AD1457', 'bg' => '#FCE4EC', 'icon' => 'undo'],
                ];
            @endphp
            @foreach($statusConfig as $status => $cfg)
                <div style="display: flex; align-items: center; gap: var(--space-sm); padding: var(--space-sm); background: {{ $cfg['bg'] }}; border-radius: var(--radius-sm);">
                    <span class="material-symbols-outlined" style="font-size: 1.1rem; color: {{ $cfg['color'] }};">{{ $cfg['icon'] }}</span>
                    <div>
                        <div style="font-size: 1.1rem; font-weight: 600; color: {{ $cfg['color'] }};">{{ $ordersByStatus[$status] ?? 0 }}</div>
                        <div style="font-size: 0.65rem; color: {{ $cfg['color'] }}; opacity: 0.8;">{{ $cfg['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Monthly Revenue Trend --}}
    <div class="admin-card">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Revenue Trend (6 Months)</h3>
        @if($monthlyRevenue->count() > 0)
            @php $maxRevenue = $monthlyRevenue->max('revenue') ?: 1; @endphp
            <div style="display: flex; flex-direction: column; gap: var(--space-md);">
                @foreach($monthlyRevenue as $month)
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px;">
                            <span style="color: var(--color-charcoal); font-weight: 500;">{{ $month['label'] }}</span>
                            <span style="color: var(--color-muted);">{{ money($month['revenue']) }} ({{ $month['orders'] }} orders)</span>
                        </div>
                        <div style="height: 8px; background: var(--color-soft-gray); border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: {{ ($month['revenue'] / $maxRevenue) * 100 }}%; background: linear-gradient(90deg, var(--color-gold), #D4A03C); border-radius: 4px; transition: width 0.5s;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: var(--color-muted); text-align: center; padding: var(--space-xl);">No revenue data yet.</p>
        @endif
    </div>
</div>

{{-- ── Top Products + Low Stock ─────────────────────────── --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-xl); margin-bottom: var(--space-xl);">
    {{-- Top Selling Products --}}
    <div class="admin-card">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm);">Top Selling Products</h3>
        @if($topProducts->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Units Sold</th>
                        <th style="text-align: right;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $i => $product)
                        <tr>
                            <td style="font-weight: 600; color: var(--color-gold);">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight: 500;">{{ $product->name }}</div>
                                <div style="font-size: 0.7rem; color: var(--color-muted);">{{ $product->sku }}</div>
                            </td>
                            <td>{{ $product->units_sold }}</td>
                            <td style="text-align: right; font-weight: 500;">{{ money($product->total_revenue) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: var(--color-muted); text-align: center; padding: var(--space-xl);">No sales data yet.</p>
        @endif
    </div>

    {{-- Low Stock Alert --}}
    <div class="admin-card">
        <h3 style="font-size: 1rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-sm); display: flex; align-items: center; gap: 6px;">
            @if($lowStockProducts->count() > 0)
                <span class="material-symbols-outlined" style="color: #C62828; font-size: 1.1rem;">warning</span>
            @endif
            Low Stock Alert
        </h3>
        @if($lowStockProducts->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th style="text-align: center;">Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                        <tr>
                            <td style="font-weight: 500;">{{ $product->name }}</td>
                            <td style="font-size: 0.8rem; color: var(--color-muted);">{{ $product->sku }}</td>
                            <td style="text-align: center;">
                                <span style="background: {{ $product->stock_quantity === 0 ? '#FFEBEE' : '#FFF3E0' }}; color: {{ $product->stock_quantity === 0 ? '#C62828' : '#E65100' }}; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline" style="padding: 0.2rem 0.5rem; font-size: 0.7rem;">Restock</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: var(--space-xl); color: var(--color-muted);">
                <span class="material-symbols-outlined" style="font-size: 2rem; opacity: 0.3;">inventory</span>
                <p style="margin-top: var(--space-sm);">All products are well stocked.</p>
            </div>
        @endif
    </div>
</div>

{{-- ── Recent Orders Table ──────────────────────────────── --}}
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.125rem; font-family: var(--font-sans);">Recent Orders</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem;">View All →</a>
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
                        <td>{{ money($order->total) }}</td>
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
                        <td colspan="6" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
