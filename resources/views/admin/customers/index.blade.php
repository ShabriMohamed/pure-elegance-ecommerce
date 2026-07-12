@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">All Customers</h2>
        <div style="font-size: 0.85rem; color: var(--color-muted);">{{ $customers->total() }} total</div>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Joined</th>
                    <th>Orders</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--space-sm);">
                                <div style="width: 36px; height: 36px; border-radius: var(--radius-full); background: var(--color-soft-gray); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem; color: var(--color-charcoal);">
                                    {{ strtoupper(substr($customer->first_name ?? $customer->name, 0, 1)) }}{{ strtoupper(substr($customer->last_name ?? '', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 500;">{{ $customer->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size: 0.85rem;">{{ $customer->email }}</td>
                        <td style="font-size: 0.85rem; color: var(--color-muted);">{{ $customer->phone ?? '—' }}</td>
                        <td style="font-size: 0.85rem; color: var(--color-muted);">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td style="font-weight: 500;">{{ $customer->orders_count ?? $customer->orders()->count() }}</td>
                        <td>
                            @if($customer->is_active)
                                <span class="badge-success">Active</span>
                            @else
                                <span class="badge-error">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: var(--space-xs);">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">View</a>
                                <form method="POST" action="{{ route('admin.customers.toggle-active', $customer) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; {{ !$customer->is_active ? 'color: var(--color-success); border-color: var(--color-success);' : 'color: #C62828; border-color: #C62828;' }}">
                                        {{ $customer->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: var(--space-lg);">
        {{ $customers->links() }}
    </div>
</div>
@endsection
