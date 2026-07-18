@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">Activity Log</h2>
        <span style="font-size: 0.75rem; color: var(--color-muted);">Admin actions audit trail</span>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>When</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Record</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td style="white-space: nowrap;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td style="font-weight: 500;">{{ $log->action }}</td>
                        <td style="color: var(--color-muted);">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</td>
                        <td style="color: var(--color-muted); font-size: 0.75rem;">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: var(--color-muted); padding: var(--space-xl);">No activity recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: var(--space-lg);">{{ $logs->links() }}</div>
</div>
@endsection
