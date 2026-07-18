<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.site_name' => 'nullable|string|max:255',
            'settings.currency_symbol' => 'nullable|string|max:8',
            'settings.contact_email' => 'nullable|email|max:255',
            'settings.contact_phone' => 'nullable|string|max:30',
            'settings.whatsapp_number' => 'nullable|string|max:30',
            'settings.whatsapp_enabled' => 'nullable|boolean',
            'settings.delivery_fee' => 'nullable|numeric|min:0',
            'settings.free_delivery_threshold' => 'nullable|numeric|min:0',
            'settings.announcement_bar_enabled' => 'nullable|boolean',
            'settings.announcement_bar_text' => 'nullable|string|max:120',
            'settings.announcement_bar_highlight' => 'nullable|string|max:60',
        ]);

        $settings = $validated['settings'];

        // Normalise the WhatsApp number to digits only (country code, no + or spaces)
        // so the wa.me link is always valid.
        if (array_key_exists('whatsapp_number', $settings) && $settings['whatsapp_number'] !== null) {
            $settings['whatsapp_number'] = preg_replace('/\D+/', '', $settings['whatsapp_number']);
        }

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'text']
            );

            // Bust the per-key cache used by SiteSetting::getValue() so edits take effect immediately.
            cache()->forget('site_setting_' . $key);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
