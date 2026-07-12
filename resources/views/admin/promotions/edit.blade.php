@extends('layouts.admin')

@section('title', 'Edit Promotion')

@section('content')
<div style="margin-bottom: var(--space-lg);">
    <a href="{{ route('admin.promotions.index') }}" style="color: var(--color-muted); font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_back</span> Back to Promotions
    </a>
</div>

<div class="admin-card" style="max-width: 700px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem;">Edit Promotion</h2>
        <div style="font-size: 0.8rem; color: var(--color-muted);">
            Used: <strong>{{ $promotion->used_count ?? 0 }}</strong>{{ $promotion->usage_limit ? ' / ' . $promotion->usage_limit : '' }} times
        </div>
    </div>

    <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Promotion Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $promotion->name) }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="code" class="form-label">Promo Code *</label>
            <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $promotion->code) }}" required style="text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
            @error('code')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="type" class="form-label">Discount Type *</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="percentage" {{ old('type', $promotion->type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    <option value="fixed" {{ old('type', $promotion->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (LKR)</option>
                </select>
                @error('type')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="value" class="form-label">Discount Value *</label>
                <input type="number" id="value" name="value" class="form-control" value="{{ old('value', $promotion->value) }}" required min="0" step="0.01">
                @error('value')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="min_order_amount" class="form-label">Minimum Order Amount</label>
                <input type="number" id="min_order_amount" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $promotion->min_order_amount) }}" min="0" step="0.01">
                @error('min_order_amount')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="max_discount_amount" class="form-label">Max Discount Cap</label>
                <input type="number" id="max_discount_amount" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount', $promotion->max_discount_amount) }}" min="0" step="0.01">
                @error('max_discount_amount')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="usage_limit" class="form-label">Usage Limit</label>
            <input type="number" id="usage_limit" name="usage_limit" class="form-control" value="{{ old('usage_limit', $promotion->usage_limit) }}" min="1">
            @error('usage_limit')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="starts_at" class="form-label">Start Date</label>
                <input type="datetime-local" id="starts_at" name="starts_at" class="form-control" value="{{ old('starts_at', $promotion->starts_at?->format('Y-m-d\TH:i')) }}">
                @error('starts_at')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="ends_at" class="form-label">End Date</label>
                <input type="datetime-local" id="ends_at" name="ends_at" class="form-control" value="{{ old('ends_at', $promotion->ends_at?->format('Y-m-d\TH:i')) }}">
                @error('ends_at')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: var(--space-sm);">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px;">
            <label for="is_active" style="margin: 0; font-size: 0.9rem;">Active</label>
        </div>

        <div style="display: flex; gap: var(--space-md); margin-top: var(--space-lg);">
            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem;">Update Promotion</button>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline" style="padding: 0.6rem 2rem;">Cancel</a>
        </div>
    </form>
</div>
@endsection
