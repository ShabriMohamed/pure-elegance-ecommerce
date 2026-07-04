@extends('layouts.admin')

@section('title', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')
<div class="admin-card" style="max-width: 600px;">
    <div style="margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">{{ isset($category) ? 'Edit Category: ' . $category->name : 'Add New Category' }}</h2>
    </div>

    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="name" class="form-label">Category Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="parent_id" class="form-label">Parent Category</label>
            <select id="parent_id" name="parent_id" class="form-control">
                <option value="">None (Top Level)</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        
        <div class="form-group">
            <label for="gender" class="form-label">Gender Category (Optional)</label>
            <select id="gender" name="gender" class="form-control">
                <option value="">None</option>
                <option value="men" {{ old('gender', $category->gender ?? '') == 'men' ? 'selected' : '' }}>Men</option>
                <option value="women" {{ old('gender', $category->gender ?? '') == 'women' ? 'selected' : '' }}>Women</option>
                <option value="unisex" {{ old('gender', $category->gender ?? '') == 'unisex' ? 'selected' : '' }}>Unisex</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="sort_order" class="form-label">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" required>
        </div>

        <div class="form-group" style="margin-top: var(--space-lg);">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                <span>Active (Visible on Storefront)</span>
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: var(--space-md); margin-top: var(--space-xl); border-top: 1px solid var(--color-border); padding-top: var(--space-lg);">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update Category' : 'Create Category' }}</button>
        </div>
    </form>
</div>
@endsection
