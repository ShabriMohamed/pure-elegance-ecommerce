@extends('layouts.admin')

@section('title', 'Banners')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">All Banners</h2>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; font-size: 0.85rem;">
            <span class="material-symbols-outlined" style="font-size: 1rem;">add</span> Add Banner
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Position</th>
                    <th>Order</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" style="width: 100px; height: 50px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $banner->title ?? 'Untitled' }}</div>
                            @if($banner->subtitle)
                                <div style="font-size: 0.75rem; color: var(--color-muted);">{{ Str::limit($banner->subtitle, 40) }}</div>
                            @endif
                        </td>
                        <td>
                            <span style="background: var(--color-soft-gray); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500; text-transform: capitalize;">
                                {{ $banner->position }}
                            </span>
                        </td>
                        <td style="text-align: center;">{{ $banner->sort_order }}</td>
                        <td style="font-size: 0.8rem; color: var(--color-muted);">
                            @if($banner->starts_at && $banner->ends_at)
                                {{ $banner->starts_at->format('M d') }} – {{ $banner->ends_at->format('M d, Y') }}
                            @elseif($banner->starts_at)
                                From {{ $banner->starts_at->format('M d, Y') }}
                            @elseif($banner->ends_at)
                                Until {{ $banner->ends_at->format('M d, Y') }}
                            @else
                                Always
                            @endif
                        </td>
                        <td>
                            @if($banner->is_active)
                                <span class="badge-success">Active</span>
                            @else
                                <span class="badge-error">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: var(--space-xs);">
                                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Delete this banner?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; color: #C62828; border-color: #C62828;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">
                            <span class="material-symbols-outlined" style="font-size: 2rem; opacity: 0.3;">image</span>
                            <p style="margin-top: var(--space-sm);">No banners yet. <a href="{{ route('admin.banners.create') }}">Create one</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
