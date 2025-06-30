<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'facebook_url' => 'nullable|url',
            'whatsapp_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
        ]);

        // Sanitize URLs to prevent double slashes and other issues
        foreach (['facebook_url', 'whatsapp_url', 'instagram_url'] as $urlField) {
            if (!empty($validated[$urlField])) {
                $validated[$urlField] = rtrim($validated[$urlField], '/');
                // Ensure proper URL format
                if (!preg_match('/^https?:\/\//', $validated[$urlField])) {
                    $validated[$urlField] = 'https://' . $validated[$urlField];
                }
            }
        }

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
