@extends('layouts.admin')

@section('title', 'Brands')

@section('breadcrumb')
<nav style="font-size: 0.8rem; color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--color-muted);">Dashboard</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <span>Brands</span>
</nav>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; gap: var(--space-md); flex-wrap: wrap; margin-bottom: var(--space-lg);">
    <div>
        <h1 style="font-size: 1.4rem;">Brands</h1>
        <p style="font-size: 0.85rem; color: var(--color-muted); margin-top: 0.25rem;">
            Logos and artwork for the storefront “Shop by Brand” showcase.
        </p>
    </div>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
        <span class="material-symbols-outlined" style="font-size: 1.1rem;">add</span> Add brand
    </a>
</div>

@if($unregistered->isNotEmpty())
    <div class="admin-card" style="margin-bottom: var(--space-lg); border-left: 3px solid var(--color-premium-gold);">
        <strong style="font-size: 0.9rem;">Brands on products without showcase assets</strong>
        <p style="font-size: 0.82rem; color: var(--color-muted); margin: 0.35rem 0 0.75rem;">
            These appear in the showcase using a monogram and a generated gradient. Add them to upload a logo and artwork.
        </p>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            @foreach($unregistered as $name)
                <a href="{{ route('admin.brands.create') }}?name={{ urlencode($name) }}"
                   class="btn btn-outline" style="padding: 6px 14px; font-size: 0.75rem;">{{ $name }}</a>
            @endforeach
        </div>
    </div>
@endif

<div class="admin-card">
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Brand</th>
                    <th>Artwork</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                    <tr>
                        <td>
                            @if($brand->logo_url)
                                <span style="display: inline-block; padding: 6px 10px; background: var(--color-rich-black); border-radius: var(--radius-sm);">
                                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-height: 26px; filter: brightness(0) invert(1); display: block;">
                                </span>
                            @else
                                <span style="display: inline-grid; place-items: center; width: 40px; height: 40px; border-radius: 50%; background: {{ $brand->accent }}; color: #fff; font-weight: 600; font-size: 0.8rem;">
                                    {{ $brand->monogram }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $brand->name }}</div>
                            @if($brand->tagline)
                                <div style="font-size: 0.75rem; color: var(--color-muted);">{{ $brand->tagline }}</div>
                            @endif
                        </td>
                        <td>
                            @if($brand->background_url)
                                <img src="{{ $brand->background_url }}" alt="" style="height: 34px; width: 70px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span style="font-size: 0.75rem; color: var(--color-muted);">Gradient</span>
                            @endif
                        </td>
                        <td>{{ $brand->sort_order }}</td>
                        <td>
                            <span style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; padding: 0.3rem 0.7rem; border-radius: var(--radius-full);
                                {{ $brand->is_active ? 'background: #E8F5E9; color: var(--color-success);' : 'background: var(--color-soft-gray); color: var(--color-muted);' }}">
                                {{ $brand->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.75rem;">Edit</a>
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" style="display: inline;"
                                  onsubmit="return confirm('Delete {{ $brand->name }}? Its logo and artwork will be removed.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 6px 12px; font-size: 0.75rem; color: var(--color-error); border-color: var(--color-error);">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: var(--space-xl); color: var(--color-muted);">
                            No brands added yet. The showcase currently uses monograms.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($brands->hasPages())
        <div style="margin-top: var(--space-lg);">{{ $brands->links() }}</div>
    @endif
</div>
@endsection
