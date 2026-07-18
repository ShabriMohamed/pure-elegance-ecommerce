@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
<div class="admin-card" style="max-width: 800px;">
    <div style="margin-bottom: var(--space-lg); border-bottom: 1px solid var(--color-border); padding-bottom: var(--space-md);">
        <h2 style="font-size: 1.25rem; font-family: var(--font-sans);">Site Configuration</h2>
        <p class="text-muted" style="font-size: 0.875rem; margin-top: 0.25rem;">Manage global settings, contact information, and business rules.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.store') }}">
        @csrf

        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); color: var(--color-charcoal);">Basic Information</h3>
        <div class="grid md-grid-cols-2 gap-4" style="margin-bottom: var(--space-xl);">
            <div class="form-group">
                <label for="site_name" class="form-label">Store Name</label>
                <input type="text" id="site_name" name="settings[site_name]" class="form-control" value="{{ $settings['site_name'] ?? 'Pure Elegance' }}">
            </div>
            <div class="form-group">
                <label for="currency_symbol" class="form-label">Currency Symbol</label>
                <input type="text" id="currency_symbol" name="settings[currency_symbol]" class="form-control" value="{{ $settings['currency_symbol'] ?? 'LKR' }}" maxlength="8">
            </div>
        </div>

        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); color: var(--color-charcoal);">Contact & Social</h3>
        <div class="grid md-grid-cols-2 gap-4" style="margin-bottom: var(--space-xl);">
            <div class="form-group">
                <label for="contact_email" class="form-label">Support Email</label>
                <input type="email" id="contact_email" name="settings[contact_email]" class="form-control" value="{{ $settings['contact_email'] ?? 'support@pureelegance.com' }}">
            </div>
            
            <div class="form-group">
                <label for="contact_phone" class="form-label">Support Phone</label>
                <input type="text" id="contact_phone" name="settings[contact_phone]" class="form-control" value="{{ $settings['contact_phone'] ?? '+94 11 234 5678' }}">
            </div>

            <div class="form-group">
                <label for="whatsapp_number" class="form-label">WhatsApp Business Number (For Orders)</label>
                <input type="text" id="whatsapp_number" name="settings[whatsapp_number]" class="form-control" value="{{ $settings['whatsapp_number'] ?? '+94770000000' }}" placeholder="e.g. +94770000000">
                <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem;">Include country code, no spaces.</div>
            </div>
        </div>

        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); color: var(--color-charcoal);">Business Rules</h3>
        <div class="grid md-grid-cols-2 gap-4" style="margin-bottom: var(--space-md);">
            <div class="form-group">
                <label for="delivery_fee" class="form-label">Delivery Fee (LKR)</label>
                <input type="number" id="delivery_fee" name="settings[delivery_fee]" class="form-control" value="{{ $settings['delivery_fee'] ?? 350 }}" step="10" min="0">
                <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem;">Flat fee charged below the free-delivery threshold.</div>
            </div>
            <div class="form-group">
                <label for="free_delivery_threshold" class="form-label">Free Delivery Threshold (LKR)</label>
                <input type="number" id="free_delivery_threshold" name="settings[free_delivery_threshold]" class="form-control" value="{{ $settings['free_delivery_threshold'] ?? 10000 }}" step="100" min="0">
                <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem;">Orders above this amount get free delivery.</div>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: var(--space-xl);">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="hidden" name="settings[whatsapp_enabled]" value="0">
                <input type="checkbox" name="settings[whatsapp_enabled]" value="1" {{ (($settings['whatsapp_enabled'] ?? '1') == '1') ? 'checked' : '' }}>
                <span>WhatsApp Checkout Enabled</span>
            </label>
            <div style="font-size: 0.75rem; color: var(--color-muted); margin-top: 0.25rem;">When off (or no number set), orders finish on an on-site confirmation page instead of WhatsApp.</div>
        </div>

        <h3 style="font-size: 1rem; margin-bottom: var(--space-md); color: var(--color-charcoal);">Announcement Bar</h3>
        <div style="margin-bottom: var(--space-lg);">
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="hidden" name="settings[announcement_bar_enabled]" value="0">
                    <input type="checkbox" name="settings[announcement_bar_enabled]" value="1" {{ (($settings['announcement_bar_enabled'] ?? '1') == '1') ? 'checked' : '' }}>
                    <span>Show announcement bar</span>
                </label>
            </div>
            <div class="grid md-grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="announcement_bar_text" class="form-label">Text</label>
                    <input type="text" id="announcement_bar_text" name="settings[announcement_bar_text]" class="form-control" value="{{ $settings['announcement_bar_text'] ?? 'FREE DELIVERY ON ORDERS OVER' }}" maxlength="120">
                </div>
                <div class="form-group">
                    <label for="announcement_bar_highlight" class="form-label">Highlight (optional — defaults to the delivery threshold)</label>
                    <input type="text" id="announcement_bar_highlight" name="settings[announcement_bar_highlight]" class="form-control" value="{{ $settings['announcement_bar_highlight'] ?? '' }}" maxlength="60">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: var(--space-xl); border-top: 1px solid var(--color-border); padding-top: var(--space-lg);">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection
