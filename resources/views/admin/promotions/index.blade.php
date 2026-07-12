@extends('layouts.admin')

@section('title', 'Promotions')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">Promotion Codes</h2>
        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 6px; padding: 0.5rem 1rem; font-size: 0.85rem;">
            <span class="material-symbols-outlined" style="font-size: 1rem;">add</span> New Promotion
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Valid Period</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $promo)
                    <tr>
                        <td style="font-weight: 500;">{{ $promo->name }}</td>
                        <td>
                            <code style="background: var(--color-soft-gray); padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600; letter-spacing: 1px;">{{ $promo->code }}</code>
                        </td>
                        <td style="font-weight: 500; color: var(--color-gold);">
                            @if($promo->type === 'percentage')
                                {{ number_format($promo->value, 0) }}%
                            @else
                                LKR {{ number_format($promo->value, 2) }}
                            @endif
                            @if($promo->max_discount_amount)
                                <div style="font-size: 0.7rem; color: var(--color-muted);">Max: LKR {{ number_format($promo->max_discount_amount, 2) }}</div>
                            @endif
                        </td>
                        <td style="font-size: 0.85rem; color: var(--color-muted);">
                            {{ $promo->min_order_amount ? 'LKR ' . number_format($promo->min_order_amount, 2) : '—' }}
                        </td>
                        <td style="font-size: 0.85rem;">
                            <span style="font-weight: 500;">{{ $promo->used_count ?? 0 }}</span>
                            @if($promo->usage_limit)
                                <span style="color: var(--color-muted);">/ {{ $promo->usage_limit }}</span>
                            @else
                                <span style="color: var(--color-muted);">/ ∞</span>
                            @endif
                        </td>
                        <td style="font-size: 0.8rem; color: var(--color-muted);">
                            @if($promo->starts_at && $promo->ends_at)
                                {{ $promo->starts_at->format('M d') }} – {{ $promo->ends_at->format('M d, Y') }}
                            @elseif($promo->starts_at)
                                From {{ $promo->starts_at->format('M d, Y') }}
                            @elseif($promo->ends_at)
                                Until {{ $promo->ends_at->format('M d, Y') }}
                            @else
                                No expiry
                            @endif
                        </td>
                        <td>
                            @if($promo->isValid())
                                <span class="badge-success">Active</span>
                            @elseif(!$promo->is_active)
                                <span class="badge-error">Disabled</span>
                            @elseif($promo->ends_at && $promo->ends_at->isPast())
                                <span class="badge-warning">Expired</span>
                            @elseif($promo->usage_limit && ($promo->used_count >= $promo->usage_limit))
                                <span class="badge-warning">Exhausted</span>
                            @else
                                <span class="badge-warning">Scheduled</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: var(--space-xs);">
                                <a href="{{ route('admin.promotions.edit', $promo) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                                <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}" onsubmit="return confirm('Delete this promotion?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; color: #C62828; border-color: #C62828;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">
                            <span class="material-symbols-outlined" style="font-size: 2rem; opacity: 0.3;">sell</span>
                            <p style="margin-top: var(--space-sm);">No promotions yet. <a href="{{ route('admin.promotions.create') }}">Create one</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
