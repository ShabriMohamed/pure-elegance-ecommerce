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
            'settings.site_name' => 'nullable|string',
            'settings.contact_email' => 'nullable|email',
            'settings.contact_phone' => 'nullable|string',
            'settings.whatsapp_number' => 'nullable|string',
            'settings.delivery_fee' => 'nullable|numeric',
            'settings.free_delivery_threshold' => 'nullable|numeric',
        ]);

        foreach ($validated['settings'] as $key => $value) {
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
