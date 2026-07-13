@extends('layouts.admin')

@section('title', 'Edit Product')

@section('breadcrumb')
<nav style="font-size: 0.8rem; color: var(--color-muted); display: flex; align-items: center; gap: 0.5rem;">
    <a href="{{ route('admin.dashboard') }}" style="color: var(--color-muted);">Dashboard</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <a href="{{ route('admin.products.index') }}" style="color: var(--color-muted);">Products</a>
    <span class="material-symbols-outlined" style="font-size: 0.9rem;">chevron_right</span>
    <span>{{ Str::limit($product->name, 40) }}</span>
</nav>
@endsection

@section('content')
<form id="product-form" method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

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
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="sku" class="form-label">SKU <span class="required-star">*</span></label>
                        <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category <span class="required-star">*</span></label>
                        <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="" disabled>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="short_description" class="form-label">Short Description</label>
                    <textarea id="short_description" name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Full Description</label>
                    <textarea id="description" name="description" class="form-control" rows="6">{{ old('description', $product->description) }}</textarea>
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
                            <input type="number" id="price" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" style="padding-left: 3rem;" required>
                        </div>
                        @error('price')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="sale_price" class="form-label">Sale Price (LKR)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #2E7D32; font-size: 0.85rem; font-weight: 600;">LKR</span>
                            <input type="number" id="sale_price" name="sale_price" step="0.01" class="form-control" value="{{ old('sale_price', $product->sale_price) }}" style="padding-left: 3rem;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity" class="form-label">Stock Quantity <span class="required-star">*</span></label>
                        <input type="number" id="stock_quantity" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required>
                        @error('stock_quantity')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Existing Image Gallery --}}
            <div class="admin-card admin-form-card">
                <div class="card-section-header">
                    <span class="material-symbols-outlined">photo_library</span>
                    <h3>Image Gallery</h3>
                    <span style="font-size: 0.75rem; color: var(--color-muted); margin-left: auto;">{{ $product->images->count() }} image{{ $product->images->count() !== 1 ? 's' : '' }}</span>
                </div>

                @if($product->images->count() > 0)
                    <div id="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 0.75rem;">
                        @foreach($product->images as $image)
                            <div class="gallery-thumb {{ $image->is_primary ? 'is-primary' : '' }}" id="thumb-{{ $image->id }}" data-image-id="{{ $image->id }}">
                                <img src="{{ $image->url }}" alt="Product image">
                                @if($image->is_primary)
                                    <div class="primary-badge">Primary</div>
                                @endif
                                <div class="gallery-actions">
                                    @if(!$image->is_primary)
                                        <button type="button" class="gallery-btn set-primary-btn" title="Set as primary"
                                            data-url="{{ route('admin.products.images.set-primary', [$product, $image]) }}">
                                            <span class="material-symbols-outlined">star</span>
                                        </button>
                                    @endif
                                    <button type="button" class="gallery-btn delete-img-btn" title="Delete image"
                                        data-url="{{ route('admin.products.images.destroy', [$product, $image]) }}"
                                        data-thumb-id="thumb-{{ $image->id }}"
                                        data-is-primary="{{ $image->is_primary ? '1' : '0' }}">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.75rem;">
                        Click <span class="material-symbols-outlined" style="font-size: 0.9rem; vertical-align: middle;">star</span> to set primary · Click <span class="material-symbols-outlined" style="font-size: 0.9rem; vertical-align: middle;">delete</span> to remove
                    </p>
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--color-muted);">
                        <span class="material-symbols-outlined" style="font-size: 2rem; display: block; opacity: 0.4;">photo_library</span>
                        No images uploaded yet.
                    </div>
                @endif
            </div>

        </div>

        {{-- RIGHT: Upload & Visibility --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem; position: sticky; top: 92px;">

            {{-- Upload New Images --}}
            <div class="admin-card admin-form-card">
                <div class="card-section-header">
                    <span class="material-symbols-outlined">cloud_upload</span>
                    <h3>Upload Images</h3>
                </div>

                {{-- Replace Primary Image --}}
                <div class="form-group">
                    <label class="form-label">Replace Primary Image</label>
                    <div id="primary-drop-zone" class="image-drop-zone" onclick="document.getElementById('primary_image').click()">
                        <div id="primary-placeholder">
                            @if($product->primaryImage)
                                <img src="{{ $product->primary_image_url }}" alt="Current" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px; position: absolute; top: 0; left: 0; opacity: 0.35;">
                            @endif
                            <span class="material-symbols-outlined" style="font-size: 2rem; color: var(--color-muted); display: block; position: relative; z-index: 1;">swap_horiz</span>
                            <div style="font-size: 0.8rem; color: var(--color-muted); position: relative; z-index: 1; margin-top: 0.25rem;">{{ $product->primaryImage ? 'Replace current primary' : 'Upload primary image' }}</div>
                        </div>
                        <img id="primary-preview" src="" alt="" style="display: none; width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                    </div>
                    <input type="file" id="primary_image" name="primary_image" accept="image/*" style="display: none;">
                    @error('primary_image')<div class="form-error" style="margin-top: 0.5rem;">{{ $message }}</div>@enderror
                </div>

                {{-- Additional Images --}}
                <div class="form-group">
                    <label class="form-label">Add More Images</label>
                    <div id="additional-drop-zone" class="image-drop-zone image-drop-zone--small" onclick="document.getElementById('additional_images').click()">
                        <span class="material-symbols-outlined" style="font-size: 1.75rem; color: var(--color-muted);">add_photo_alternate</span>
                        <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem;">Add more images</div>
                    </div>
                    <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple style="display: none;">
                    <div id="additional-previews" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-top: 0.75rem;"></div>
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
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <span class="toggle-track"><span class="toggle-thumb"></span></span>
                    </div>
                </label>

                <label class="toggle-label" style="margin-top: 0.75rem;">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.9rem;">Featured</div>
                        <div style="font-size: 0.78rem; color: var(--color-muted);">Show in featured section</div>
                    </div>
                    <div class="toggle-wrapper">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <span class="toggle-track"><span class="toggle-thumb"></span></span>
                    </div>
                </label>
            </div>

            {{-- Actions --}}
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary btn-block" style="padding: 0.9rem; font-size: 0.95rem;">
                    <span class="material-symbols-outlined" style="font-size: 1.1rem; vertical-align: middle;">save</span>
                    Save Changes
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-block" style="padding: 0.7rem; text-align: center;">
                    Cancel
                </a>
            </div>

        </div>
    </div>
</form>

{{-- Delete form is intentionally OUTSIDE the update form to prevent nested form bugs --}}
<div style="max-width: 380px; margin-left: auto; margin-top: 0.5rem;">
    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product permanently? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline btn-block" style="color: #C62828; border-color: #C62828; padding: 0.7rem; width: 100%;">
            <span class="material-symbols-outlined" style="font-size: 1rem; vertical-align: middle;">delete_forever</span> Delete Product
        </button>
    </form>
</div>

<div id="toast-notification" style="position: fixed; bottom: 2rem; right: 2rem; z-index: 9999; display: none;">
    <div style="background: #1A1A1D; color: white; padding: 0.9rem 1.25rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 0.6rem; box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
        <span id="toast-icon" class="material-symbols-outlined" style="font-size: 1.1rem;"></span>
        <span id="toast-message"></span>
    </div>
</div>

@push('styles')
<style>
    .admin-form-card { transition: none !important; transform: none !important; }
    .admin-form-card:hover { transform: none !important; box-shadow: var(--admin-card-shadow) !important; }

    .card-section-header { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(0,0,0,0.05); }
    .card-section-header span.material-symbols-outlined { color: var(--color-gold); }
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
    .image-drop-zone--small { height: 80px; }

    .gallery-thumb {
        position: relative; aspect-ratio: 1; border-radius: 10px;
        overflow: hidden; background: #F5F5F5;
        border: 2px solid transparent; transition: border-color 0.2s;
    }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .gallery-thumb.is-primary { border-color: var(--color-gold); }
    .primary-badge {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: var(--color-gold); color: white;
        font-size: 0.65rem; font-weight: 700; text-align: center; padding: 3px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }
    .gallery-actions {
        position: absolute; top: 4px; right: 4px;
        display: none; flex-direction: column; gap: 4px;
    }
    .gallery-thumb:hover .gallery-actions { display: flex; }
    .gallery-btn {
        width: 26px; height: 26px; border-radius: 6px; border: none;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all 0.15s; backdrop-filter: blur(4px);
    }
    .gallery-btn .material-symbols-outlined { font-size: 0.9rem; }
    .set-primary-btn { background: rgba(212,175,55,0.85); color: white; }
    .set-primary-btn:hover { background: var(--color-gold); }
    .delete-img-btn { background: rgba(198,40,40,0.85); color: white; }
    .delete-img-btn:hover { background: #C62828; }

    .additional-thumb { aspect-ratio: 1; border-radius: 8px; overflow: hidden; position: relative; background: #F5F5F5; border: 1px solid rgba(0,0,0,0.08); }
    .additional-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .additional-thumb .remove-thumb { position: absolute; top: 4px; right: 4px; background: rgba(0,0,0,0.65); color: white; border: none; border-radius: 50%; width: 22px; height: 22px; font-size: 0.7rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .additional-thumb .remove-thumb:hover { background: #C62828; }

    .toggle-label { display: flex; align-items: center; gap: 1rem; cursor: pointer; padding: 0.75rem; border-radius: 10px; transition: background 0.2s; }
    .toggle-label:hover { background: rgba(0,0,0,0.02); }
    .toggle-wrapper input[type="checkbox"] { display: none; }
    .toggle-track { width: 44px; height: 24px; background: #CBD5E1; border-radius: 12px; position: relative; transition: background 0.2s; display: block; flex-shrink: 0; }
    .toggle-thumb { width: 18px; height: 18px; background: white; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
    .toggle-wrapper input:checked + .toggle-track { background: var(--color-gold); }
    .toggle-wrapper input:checked + .toggle-track .toggle-thumb { transform: translateX(20px); }

    @media (max-width: 1100px) {
        form > div { grid-template-columns: 1fr !important; }
        .form-grid-3 { grid-template-columns: 1fr 1fr !important; }
    }
    @media (max-width: 600px) {
        .form-grid-2, .form-grid-3 { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast-notification');
    const icon = document.getElementById('toast-icon');
    const msg = document.getElementById('toast-message');
    icon.textContent = type === 'success' ? 'check_circle' : 'error';
    icon.style.color = type === 'success' ? '#4CAF50' : '#EF5350';
    msg.textContent = message;
    toast.style.display = 'block';
    toast.style.animation = 'none';
    setTimeout(() => { toast.style.display = 'none'; }, 3500);
}

// Delete image
document.querySelectorAll('.delete-img-btn').forEach(btn => {
    btn.addEventListener('click', async function () {
        if (!confirm('Delete this image? This cannot be undone.')) return;
        const url = this.dataset.url;
        const thumbId = this.dataset.thumbId;
        try {
            const resp = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            const data = await resp.json();
            if (data.success) {
                document.getElementById(thumbId)?.remove();
                showToast('Image deleted successfully.');
            } else {
                showToast(data.message || 'Failed to delete image.', 'error');
            }
        } catch (e) {
            showToast('An error occurred. Please try again.', 'error');
        }
    });
});

// Set primary image
document.querySelectorAll('.set-primary-btn').forEach(btn => {
    btn.addEventListener('click', async function () {
        const url = this.dataset.url;
        const thumbEl = this.closest('.gallery-thumb');
        try {
            const resp = await fetch(url, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            const data = await resp.json();
            if (data.success) {
                // Update UI: remove primary from all
                document.querySelectorAll('.gallery-thumb').forEach(t => {
                    t.classList.remove('is-primary');
                    t.querySelector('.primary-badge')?.remove();
                });
                // Set primary on clicked
                thumbEl.classList.add('is-primary');
                const badge = document.createElement('div');
                badge.className = 'primary-badge';
                badge.textContent = 'Primary';
                thumbEl.appendChild(badge);
                // Remove star button from this thumb
                this.remove();
                showToast('Primary image updated.');
            } else {
                showToast(data.message || 'Failed to set primary.', 'error');
            }
        } catch (e) {
            showToast('An error occurred. Please try again.', 'error');
        }
    });
});

// Primary image preview
const primaryInput = document.getElementById('primary_image');
const primaryPreview = document.getElementById('primary-preview');
const primaryPlaceholder = document.getElementById('primary-placeholder');
const primaryDropZone = document.getElementById('primary-drop-zone');

primaryInput.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            primaryPreview.src = e.target.result;
            primaryPreview.style.display = 'block';
            primaryPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(this.files[0]);
    }
});

['dragenter','dragover'].forEach(ev => primaryDropZone.addEventListener(ev, e => { e.preventDefault(); primaryDropZone.classList.add('drag-over'); }));
['dragleave','drop'].forEach(ev => primaryDropZone.addEventListener(ev, e => { e.preventDefault(); primaryDropZone.classList.remove('drag-over'); }));
primaryDropZone.addEventListener('drop', e => {
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer(); dt.items.add(file); primaryInput.files = dt.files;
        const reader = new FileReader();
        reader.onload = ev => { primaryPreview.src = ev.target.result; primaryPreview.style.display = 'block'; primaryPlaceholder.style.display = 'none'; };
        reader.readAsDataURL(file);
    }
});

// Additional images preview
const additionalInput = document.getElementById('additional_images');
const previewContainer = document.getElementById('additional-previews');
const additionalDropZone = document.getElementById('additional-drop-zone');
let selectedFiles = [];

additionalInput.addEventListener('change', function () {
    Array.from(this.files).forEach(addPreview);
});

function addPreview(file) {
    if (selectedFiles.length >= 10) return;
    selectedFiles.push(file);
    syncFiles();
    const wrapper = document.createElement('div'); wrapper.className = 'additional-thumb';
    const img = document.createElement('img');
    new FileReader().onload = e => img.src = e.target.result;
    (r => { r.onload = e => img.src = e.target.result; r.readAsDataURL(file); })(new FileReader());
    const btn = document.createElement('button'); btn.type = 'button'; btn.className = 'remove-thumb'; btn.innerHTML = '✕';
    btn.addEventListener('click', () => { selectedFiles.splice(selectedFiles.indexOf(file), 1); syncFiles(); wrapper.remove(); });
    wrapper.append(img, btn);
    previewContainer.appendChild(wrapper);
}
function syncFiles() { const dt = new DataTransfer(); selectedFiles.forEach(f => dt.items.add(f)); additionalInput.files = dt.files; }

['dragenter','dragover'].forEach(ev => additionalDropZone.addEventListener(ev, e => { e.preventDefault(); additionalDropZone.classList.add('drag-over'); }));
['dragleave','drop'].forEach(ev => additionalDropZone.addEventListener(ev, e => { e.preventDefault(); additionalDropZone.classList.remove('drag-over'); }));
additionalDropZone.addEventListener('drop', e => Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')).forEach(addPreview));
</script>
@endpush
@endsection
