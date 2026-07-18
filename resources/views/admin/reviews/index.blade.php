@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">Product Reviews</h2>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td style="font-weight: 500;">{{ $review->product->name ?? '—' }}</td>
                        <td>{{ $review->user->name ?? '—' }}</td>
                        <td>{{ $review->rating }}/5</td>
                        <td style="max-width: 280px; color: var(--color-muted);">{{ \Illuminate\Support\Str::limit($review->comment, 80) ?: '—' }}</td>
                        <td>
                            @if($review->is_approved)
                                <span class="badge-success">Approved</span>
                            @else
                                <span class="badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                        <td style="white-space: nowrap;">
                            @unless($review->is_approved)
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display: inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.72rem;">Approve</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" style="display: inline;" onsubmit="return confirm('Delete this review?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.72rem; color: var(--color-error); border-color: var(--color-error);">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No reviews yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: var(--space-lg);">{{ $reviews->links() }}</div>
</div>
@endsection
