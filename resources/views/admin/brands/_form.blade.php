@csrf

<div class="form-group">
    <label class="form-label" for="name">Brand name <span style="color: var(--color-error);">*</span></label>
    <input type="text" name="name" id="name" class="form-control" required maxlength="120"
           value="{{ old('name', $brand->name) }}" placeholder="e.g. Levi's">
    <p style="font-size: 0.72rem; color: var(--color-muted); margin-top: 6px;">
        Must match the brand spelling used on products exactly, or the showcase can't link them.
    </p>
    @error('name')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label class="form-label" for="tagline">Tagline</label>
    <input type="text" name="tagline" id="tagline" class="form-control" maxlength="160"
           value="{{ old('tagline', $brand->tagline) }}" placeholder="Optional line shown under the logo">
    @error('tagline')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label class="form-label" for="logo">Brand logo</label>
    @if($brand->logo_url)
        <div style="margin-bottom: 0.5rem; padding: 0.75rem; background: var(--color-rich-black); border-radius: var(--radius-sm); display: inline-block;">
            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-height: 54px; filter: brightness(0) invert(1);">
        </div>
    @endif
    <input type="file" name="logo" id="logo" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
    <p style="font-size: 0.72rem; color: var(--color-muted); margin-top: 6px;">
        PNG with transparency works best. Logos are displayed in white on the dark slide,
        so a solid single-colour mark reproduces most reliably. Max 2 MB.
    </p>
    @error('logo')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label class="form-label" for="background">Background image</label>
    @if($brand->background_url)
        <div style="margin-bottom: 0.5rem;">
            <img src="{{ $brand->background_url }}" alt="" style="max-height: 120px; border-radius: var(--radius-sm);">
        </div>
    @endif
    <input type="file" name="background" id="background" class="form-control" accept="image/png,image/jpeg,image/webp">
    <p style="font-size: 0.72rem; color: var(--color-muted); margin-top: 6px;">
        Landscape artwork, ideally 1920&times;820 or wider. Only upload imagery you have the
        rights to use. Leave empty to fall back to a generated accent gradient. Max 5 MB.
    </p>
    @error('background')<div class="form-error">{{ $message }}</div>@enderror
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: var(--space-md);">
    <div class="form-group">
        <label class="form-label" for="accent_color">Accent colour</label>
        <input type="text" name="accent_color" id="accent_color" class="form-control"
               value="{{ old('accent_color', $brand->accent_color) }}" placeholder="#7B1E2B">
        <p style="font-size: 0.72rem; color: var(--color-muted); margin-top: 6px;">
            Used for the gradient when no background is uploaded.
        </p>
        @error('accent_color')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="sort_order">Sort order</label>
        <input type="number" name="sort_order" id="sort_order" class="form-control" min="0" max="9999"
               value="{{ old('sort_order', $brand->sort_order ?? 0) }}">
        @error('sort_order')<div class="form-error">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer;">
        <input type="checkbox" name="is_active" value="1"
               {{ old('is_active', $brand->exists ? $brand->is_active : true) ? 'checked' : '' }}>
        <span>Show in the Shop by Brand showcase</span>
    </label>
</div>

<div style="display: flex; gap: var(--space-md); align-items: center; margin-top: var(--space-lg);">
    <button type="submit" class="btn btn-primary">{{ $brand->exists ? 'Update brand' : 'Create brand' }}</button>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline">Cancel</a>
</div>
