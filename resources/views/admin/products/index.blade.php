@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">All Products</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">
            <span class="material-symbols-outlined">add</span> Add Product
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div style="width: 40px; height: 50px; background: var(--color-cream); border-radius: var(--radius-sm); overflow: hidden;">
                                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </td>
                        <td style="font-weight: 500;">{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category->name ?? 'None' }}</td>
                        <td>LKR {{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td>
                            @if($product->is_active)
                                <span class="badge-success">Active</span>
                            @else
                                <span class="badge-error">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: var(--space-sm);">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="color: var(--color-error); border-color: var(--color-error); padding: 0.25rem 0.5rem; font-size: 0.75rem;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: var(--space-lg);">
        {{ $products->links() }}
    </div>
</div>
@endsection
