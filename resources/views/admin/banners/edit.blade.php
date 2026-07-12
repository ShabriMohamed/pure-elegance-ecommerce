@extends('layouts.admin')

@section('title', 'Edit Banner')

@section('content')
<div style="margin-bottom: var(--space-lg);">
    <a href="{{ route('admin.banners.index') }}" style="color: var(--color-muted); font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        <span class="material-symbols-outlined" style="font-size: 1rem;">arrow_back</span> Back to Banners
    </a>
</div>

<div class="admin-card" style="max-width: 700px;">
    <h2 style="font-size: 1.25rem; margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">Edit Banner</h2>

    {{-- Current Image Preview --}}
    <div style="margin-bottom: var(--space-lg);">
        <label class="form-label">Current Image</label>
        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: var(--radius-md); border: 1px solid var(--color-border);">
    </div>

    <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $banner->title) }}" placeholder="Banner headline">
            @error('title')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="subtitle" class="form-label">Subtitle</label>
            <input type="text" id="subtitle" name="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle) }}" placeholder="Supporting text">
            @error('subtitle')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="cta_text" class="form-label">Button Text</label>
                <input type="text" id="cta_text" name="cta_text" class="form-control" value="{{ old('cta_text', $banner->cta_text) }}" placeholder="e.g. Shop Now">
                @error('cta_text')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="cta_link" class="form-label">Button Link</label>
                <input type="text" id="cta_link" name="cta_link" class="form-control" value="{{ old('cta_link', $banner->cta_link) }}" placeholder="/shop">
                @error('cta_link')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="position" class="form-label">Position *</label>
                <select id="position" name="position" class="form-control" required>
                    <option value="hero" {{ old('position', $banner->position) === 'hero' ? 'selected' : '' }}>Hero</option>
                    <option value="category" {{ old('position', $banner->position) === 'category' ? 'selected' : '' }}>Category</option>
                    <option value="promotional" {{ old('position', $banner->position) === 'promotional' ? 'selected' : '' }}>Promotional</option>
                </select>
                @error('position')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="sort_order" class="form-label">Sort Order *</label>
                <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order) }}" min="0" required>
                @error('sort_order')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="image" class="form-label">Replace Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" style="padding: 0.5rem;">
            <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 4px;">Leave empty to keep current image. Max 2MB.</div>
            @error('image')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-md);">
            <div class="form-group">
                <label for="starts_at" class="form-label">Start Date</label>
                <input type="datetime-local" id="starts_at" name="starts_at" class="form-control" value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d\TH:i')) }}">
                @error('starts_at')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="ends_at" class="form-label">End Date</label>
                <input type="datetime-local" id="ends_at" name="ends_at" class="form-control" value="{{ old('ends_at', $banner->ends_at?->format('Y-m-d\TH:i')) }}">
                @error('ends_at')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group" style="display: flex; align-items: center; gap: var(--space-sm);">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px;">
            <label for="is_active" style="margin: 0; font-size: 0.9rem;">Active</label>
        </div>

        <div style="display: flex; gap: var(--space-md); margin-top: var(--space-lg);">
            <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem;">Update Banner</button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline" style="padding: 0.6rem 2rem;">Cancel</a>
        </div>
    </form>
</div>
@endsection
