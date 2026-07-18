@extends('layouts.admin')

@section('title', 'Add Brand')

@section('breadcrumb')
<nav style="font-size: 0.8rem; color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--color-muted);">Dashboard</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <a href="{{ route('admin.brands.index') }}" style="color: var(--color-muted);">Brands</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <span>Add</span>
</nav>
@endsection

@section('content')
<div class="admin-card" style="max-width: 720px;">
    <h2 style="font-size: 1.15rem; margin-bottom: var(--space-lg);">Add a brand</h2>
    <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
        @include('admin.brands._form')
    </form>
</div>
@endsection
