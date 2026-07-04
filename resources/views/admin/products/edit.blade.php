@extends('layouts.admin')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
<div class="admin-card" style="max-width: 800px;">
    <div style="margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">{{ isset($product) ? 'Edit Product: ' . $product->name : 'Add New Product' }}</h2>
    </div>

    <form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="grid md-grid-cols-2 gap-4">
            <div class="form-group">
                <label for="name" class="form-label">Product Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label for="sku" class="form-label">SKU *</label>
                <input type="text" id="sku" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}" required>
                @error('sku')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid md-grid-cols-2 gap-4">
            <div class="form-group">
                <label for="category_id" class="form-label">Category *</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand', $product->brand ?? '') }}">
            </div>
        </div>

        <div class="grid md-grid-cols-3 gap-4">
            <div class="form-group">
                <label for="price" class="form-label">Regular Price (LKR) *</label>
                <input type="number" id="price" name="price" step="0.01" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
                @error('price')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label for="sale_price" class="form-label">Sale Price (LKR)</label>
                <input type="number" id="sale_price" name="sale_price" step="0.01" class="form-control" value="{{ old('sale_price', $product->sale_price ?? '') }}">
            </div>
            
            <div class="form-group">
                <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="short_description" class="form-label">Short Description</label>
            <textarea id="short_description" name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description ?? '') }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="description" class="form-label">Full Description</label>
            <textarea id="description" name="description" class="form-control" rows="5">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label for="primary_image" class="form-label">Primary Image {{ isset($product) ? '(Leave empty to keep current)' : '*' }}</label>
            <input type="file" id="primary_image" name="primary_image" class="form-control" accept="image/*" {{ !isset($product) ? 'required' : '' }}>
            @if(isset($product) && $product->primaryImage)
                <div style="margin-top: var(--space-sm);">
                    <img src="{{ $product->primary_image_url }}" alt="Current Image" style="width: 100px; border-radius: var(--radius-sm);">
                </div>
            @endif
            @error('primary_image')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="display: flex; gap: var(--space-xl); margin-top: var(--space-lg);">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                <span>Active (Visible on Storefront)</span>
            </label>
            
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                <span>Featured Product</span>
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: var(--space-md); margin-top: var(--space-xl); border-top: 1px solid var(--color-border); padding-top: var(--space-lg);">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update Product' : 'Create Product' }}</button>
        </div>
    </form>
</div>
@endsection
