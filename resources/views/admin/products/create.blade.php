@extends('layouts.admin')

@section('title', 'Add Product')

@section('breadcrumb')
<nav style="font-size: 0.8rem; color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--color-muted);">Dashboard</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <a href="{{ route('admin.products.index') }}" style="color: var(--color-muted);">Products</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <span>Add New</span>
</nav>
@endsection

@section('content')
<form id="product-form" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start;">

        {{-- LEFT: Product Details --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Basic Info --}}
            <div class="admin-card admin-form-card">
                <div class="card-section-header">
                    <span class="material-symbols-outlined">info</span>
                    <h3>Basic Information</h3>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="name" class="form-label">Product Name <span class="required-star">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Pure Silk Evening Gown" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="sku" class="form-label">SKU <span class="required-star">*</span></label>
                        <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="e.g. PE-DRESS-001" required>
                        @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category <span class="required-star">*</span></label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="e.g. Pure Elegance">
                    </div>
                </div>

                <div class="form-group">
                    <label for="short_description" class="form-label">Short Description</label>
                    <textarea id="short_description" name="short_description" class="form-control" rows="2" placeholder="Brief product summary shown in listings...">{{ old('short_description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Full Description</label>
                    <textarea id="description" name="description" class="form-control" rows="6" placeholder="Detailed product description, features, materials...">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="meta_title" class="form-label">Meta Title (SEO, optional)</label>
                    <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title') }}" maxlength="255">
                    @error('meta_title')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="meta_description" class="form-label">Meta Description (SEO, optional)</label>
                    <textarea id="meta_description" name="meta_description" class="form-control" rows="2" maxlength="255">{{ old('meta_description') }}</textarea>
                    @error('meta_description')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Pricing & Inventory --}}
            <div class="admin-card admin-form-card">
                <div class="card-section-header">
                    <span class="material-symbols-outlined">payments</span>
                    <h3>Pricing & Inventory</h3>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label for="price" class="form-label">Regular Price (LKR) <span class="required-star">*</span></label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--color-muted); font-size: 0.85rem; font-weight: 600;">LKR</span>
                            <input type="number" id="price" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" style="padding-left: 3rem;" placeholder="0.00" required>
                        </div>
                        @error('price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="sale_price" class="form-label">Sale Price (LKR)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #2E7D32; font-size: 0.85rem; font-weight: 600;">LKR</span>
                            <input type="number" id="sale_price" name="sale_price" step="0.01" class="form-control" value="{{ old('sale_price') }}" style="padding-left: 3rem;" placeholder="Optional">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity" class="form-label">Stock Quantity <span class="required-star">*</span></label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', 0) }}" min="0" required>
                        @error('stock_quantity')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT: Images & Visibility --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem; position: sticky; top: 92px;">

            {{-- Images --}}
            <div class="admin-card admin-form-card">
                <div class="card-section-header">
                    <span class="material-symbols-outlined">photo_library</span>
                    <h3>Product Images</h3>
                </div>

                {{-- Primary Image Upload --}}
                <div class="form-group">
                    <label class="form-label">Primary Image <span class="required-star">*</span></label>
                    <div id="primary-drop-zone" class="image-drop-zone" onclick="document.getElementById('primary_image').click()">
                        <div id="primary-placeholder">
                            <span class="material-symbols-outlined" style="font-size: 2.5rem; color: var(--color-muted); display: block; margin-bottom: 0.5rem;">add_photo_alternate</span>
                            <div style="font-weight: 600; color: var(--color-charcoal); font-size: 0.9rem;">Click or drag to upload</div>
                            <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem;">Primary display image · Max 4MB</div>
                        </div>
                        <img id="primary-preview" src="" alt="" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                    </div>
                    <input type="file" id="primary_image" name="primary_image" accept="image/*" style="display: none;" required>
                    @error('primary_image')<div class="form-error" style="margin-top: 0.5rem;">{{ $message }}</div>@enderror
                </div>

                {{-- Additional Images --}}
                <div class="form-group">
                    <label class="form-label">Additional Images</label>
                    <div id="additional-drop-zone" class="image-drop-zone image-drop-zone--small" onclick="document.getElementById('additional_images').click()">
                        <span class="material-symbols-outlined" style="font-size: 1.75rem; color: var(--color-muted);">add</span>
                        <div style="font-size: 0.8rem; color: var(--color-muted); margin-top: 0.25rem;">Add more images (up to 10)</div>
                    </div>
                    <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple style="display: none;">

                    <div id="additional-previews" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-top: 0.75rem;"></div>
                    @error('additional_images.*')<div class="form-error" style="margin-top: 0.5rem;">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Visibility --}}
            <div class="admin-card admin-form-card">
                <div class="card-section-header">
                    <span class="material-symbols-outlined">visibility</span>
                    <h3>Visibility & Status</h3>
                </div>

                <label class="toggle-label">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.9rem;">Active</div>
                        <div style="font-size: 0.78rem; color: var(--color-muted);">Visible on storefront</div>
                    </div>
                    <div class="toggle-wrapper">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="toggle-track"><span class="toggle-thumb"></span></span>
                    </div>
                </label>

                <label class="toggle-label" style="margin-top: 0.75rem;">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.9rem;">Featured</div>
                        <div style="font-size: 0.78rem; color: var(--color-muted);">Show in featured section</div>
                    </div>
                    <div class="toggle-wrapper">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <span class="toggle-track"><span class="toggle-thumb"></span></span>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary btn-block" style="padding: 0.9rem; font-size: 0.95rem;">
                    <span class="material-symbols-outlined" style="font-size: 1.1rem; vertical-align: middle;">save</span>
                    Create Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-block" style="padding: 0.7rem; text-align: center;">
                    Cancel
                </a>
            </div>

        </div>
    </div>
</form>

@push('styles')
<style>
    .admin-form-card { transition: none !important; transform: none !important; }
    .admin-form-card:hover { transform: none !important; box-shadow: var(--admin-card-shadow) !important; }

    .card-section-header {
        display: flex; align-items: center; gap: 0.6rem;
        margin-bottom: 1.25rem; padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .card-section-header span { color: var(--color-gold); }
    .card-section-header h3 { font-size: 0.95rem; font-weight: 600; margin: 0; }

    .required-star { color: #C62828; }

    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }

    .image-drop-zone {
        width: 100%; height: 180px;
        border: 2px dashed rgba(0,0,0,0.15); border-radius: 12px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s ease; background: rgba(0,0,0,0.01);
        overflow: hidden; position: relative;
    }
    .image-drop-zone:hover { border-color: var(--color-gold); background: rgba(212,175,55,0.04); }
    .image-drop-zone.drag-over { border-color: var(--color-gold); background: rgba(212,175,55,0.08); transform: scale(1.01); }
    .image-drop-zone--small { height: 80px; }

    .additional-thumb {
        aspect-ratio: 1; border-radius: 8px; overflow: hidden;
        position: relative; background: #F5F5F5; border: 1px solid rgba(0,0,0,0.08);
    }
    .additional-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .additional-thumb .remove-thumb {
        position: absolute; top: 4px; right: 4px;
        background: rgba(0,0,0,0.65); color: white; border: none;
        border-radius: 50%; width: 22px; height: 22px;
        font-size: 0.7rem; cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .additional-thumb .remove-thumb:hover { background: #C62828; }

    .toggle-label {
        display: flex; align-items: center; gap: 1rem; cursor: pointer;
        padding: 0.75rem; border-radius: 10px; transition: background 0.2s;
    }
    .toggle-label:hover { background: rgba(0,0,0,0.02); }
    .toggle-wrapper input[type="checkbox"] { display: none; }
    .toggle-track {
        width: 44px; height: 24px; background: #CBD5E1; border-radius: 12px;
        position: relative; transition: background 0.2s; display: block; flex-shrink: 0;
    }
    .toggle-thumb {
        width: 18px; height: 18px; background: white; border-radius: 50%;
        position: absolute; top: 3px; left: 3px; transition: transform 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .toggle-wrapper input:checked + .toggle-track { background: var(--color-gold); }
    .toggle-wrapper input:checked + .toggle-track .toggle-thumb { transform: translateX(20px); }

    @media (max-width: 1100px) {
        form > div { grid-template-columns: 1fr !important; }
        .right-sidebar { position: static !important; }
        .form-grid-3 { grid-template-columns: 1fr 1fr !important; }
    }
    @media (max-width: 600px) {
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Primary image preview
    const primaryInput = document.getElementById('primary_image');
    const primaryPreview = document.getElementById('primary-preview');
    const primaryPlaceholder = document.getElementById('primary-placeholder');
    const primaryDropZone = document.getElementById('primary-drop-zone');

    primaryInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            showPrimaryPreview(this.files[0]);
        }
    });

    function showPrimaryPreview(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            primaryPreview.src = e.target.result;
            primaryPreview.style.display = 'block';
            primaryPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // Drag and drop for primary
    ['dragenter', 'dragover'].forEach(ev => primaryDropZone.addEventListener(ev, e => { e.preventDefault(); primaryDropZone.classList.add('drag-over'); }));
    ['dragleave', 'drop'].forEach(ev => primaryDropZone.addEventListener(ev, e => { e.preventDefault(); primaryDropZone.classList.remove('drag-over'); }));
    primaryDropZone.addEventListener('drop', function (e) {
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            primaryInput.files = dt.files;
            showPrimaryPreview(file);
        }
    });

    // Additional images preview
    const additionalInput = document.getElementById('additional_images');
    const previewContainer = document.getElementById('additional-previews');
    const additionalDropZone = document.getElementById('additional-drop-zone');
    let selectedFiles = [];

    additionalInput.addEventListener('change', function () {
        Array.from(this.files).forEach(addAdditionalPreview);
    });

    function addAdditionalPreview(file) {
        if (selectedFiles.length >= 10) return;
        selectedFiles.push(file);
        syncFilesToInput();

        const wrapper = document.createElement('div');
        wrapper.className = 'additional-thumb';
        const img = document.createElement('img');
        const reader = new FileReader();
        reader.onload = e => img.src = e.target.result;
        reader.readAsDataURL(file);
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'remove-thumb';
        btn.innerHTML = '✕';
        btn.addEventListener('click', function () {
            const idx = selectedFiles.indexOf(file);
            if (idx > -1) selectedFiles.splice(idx, 1);
            syncFilesToInput();
            wrapper.remove();
        });
        wrapper.appendChild(img);
        wrapper.appendChild(btn);
        previewContainer.appendChild(wrapper);
    }

    function syncFilesToInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        additionalInput.files = dt.files;
    }

    ['dragenter', 'dragover'].forEach(ev => additionalDropZone.addEventListener(ev, e => { e.preventDefault(); additionalDropZone.classList.add('drag-over'); }));
    ['dragleave', 'drop'].forEach(ev => additionalDropZone.addEventListener(ev, e => { e.preventDefault(); additionalDropZone.classList.remove('drag-over'); }));
    additionalDropZone.addEventListener('drop', function (e) {
        Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')).forEach(addAdditionalPreview);
    });
});
</script>
@endpush
@endsection
