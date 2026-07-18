@extends('layouts.admin')

@section('title', 'Products')

@section('breadcrumb')
<nav style="font-size: 0.8rem; color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--color-muted);">Dashboard</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <span>Products</span>
</nav>
@endsection

@section('content')
<div class="admin-card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="margin-bottom: 0.35rem;">Search Products</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--color-muted); font-size: 1.1rem; pointer-events: none;">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, SKU or brand..." class="form-control" style="padding-left: 2.5rem;">
            </div>
        </div>
        <div style="min-width: 160px;">
            <label class="form-label" style="margin-bottom: 0.35rem;">Status</label>
            <select name="status" class="form-control">
                <option value="">All Products</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1.25rem;">
                <span class="material-symbols-outlined" style="font-size: 1rem;">filter_list</span> Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="padding: 0.6rem 1rem;">Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.125rem; font-weight: 600; font-family: var(--font-sans); margin: 0;">All Products</h2>
            <p style="font-size: 0.8rem; color: var(--color-muted); margin: 0.25rem 0 0;">
                {{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }} found
            </p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.4rem;">
            <span class="material-symbols-outlined" style="font-size: 1.1rem;">add</span> Add Product
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div style="width: 52px; height: 60px; background: #F5F5F5; border-radius: 10px; overflow: hidden; border: 1px solid rgba(0,0,0,0.06);">
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $product->name }}</div>
                            @if($product->brand)
                                <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 2px;">{{ $product->brand }}</div>
                            @endif
                            <div style="font-size: 0.7rem; color: var(--color-muted); margin-top: 3px;">
                                <span class="material-symbols-outlined" style="font-size: 0.8rem; vertical-align: middle;">photo_library</span>
                                {{ $product->images_count ?? $product->images->count() }} image{{ ($product->images_count ?? $product->images->count()) !== 1 ? 's' : '' }}
                            </div>
                        </td>
                        <td style="font-family: monospace; font-size: 0.85rem; color: var(--color-muted);">{{ $product->sku }}</td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ money($product->price) }}</div>
                            @if($product->sale_price)
                                <div style="font-size: 0.75rem; color: #2E7D32; margin-top: 1px;">Sale: {{ money($product->sale_price) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($product->stock_quantity <= 0)
                                <span class="badge-error">Out of Stock</span>
                            @elseif($product->stock_quantity <= 5)
                                <span class="badge-warning">Low: {{ $product->stock_quantity }}</span>
                            @else
                                <span style="font-weight: 600;">{{ $product->stock_quantity }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge-success">Active</span>
                            @else
                                <span class="badge-error">Inactive</span>
                            @endif
                            @if($product->is_featured)
                                <span class="badge-warning" style="display: block; margin-top: 4px;">Featured</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline" style="padding: 0.3rem 0.65rem; font-size: 0.78rem; display: flex; align-items: center; gap: 0.25rem;">
                                    <span class="material-symbols-outlined" style="font-size: 0.9rem;">edit</span> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="color: #C62828; border-color: #C62828; padding: 0.3rem 0.65rem; font-size: 0.78rem; display: flex; align-items: center; gap: 0.25rem;">
                                        <span class="material-symbols-outlined" style="font-size: 0.9rem;">delete</span> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem; color: var(--color-muted);">
                            <span class="material-symbols-outlined" style="font-size: 3rem; display: block; margin-bottom: 0.75rem; opacity: 0.4;">inventory_2</span>
                            @if(request()->hasAny(['search', 'status']))
                                No products match your search criteria.
                                <br><a href="{{ route('admin.products.index') }}" style="color: var(--color-gold);">Clear filters</a>
                            @else
                                No products found. <a href="{{ route('admin.products.create') }}" style="color: var(--color-gold);">Add your first product.</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.04);">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
