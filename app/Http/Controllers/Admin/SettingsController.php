<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Handle file uploads for image type settings
                if ($setting->type === 'image' && $request->hasFile("settings.$key")) {
                    // Delete old image if exists
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    
                    $path = $request->file("settings.$key")->store('branding', 'public');
                    $setting->value = $path;
                } elseif ($setting->type === 'json') {
                    // Handle JSON arrays (like time slots)
                    if (is_array($value)) {
                        $setting->value = json_encode(array_values($value));
                    } else {
                        $setting->value = $value;
                    }
                } elseif ($setting->type === 'boolean') {
                    // Handle boolean values
                    $setting->value = $request->has("settings.$key") ? '1' : '0';
                } else {
                    // For non-image fields, update value directly
                    if (!$request->hasFile("settings.$key")) {
                        $setting->value = $value;
                    }
                }
                
                $setting->save();
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
