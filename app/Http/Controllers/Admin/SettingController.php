<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $groups = Setting::orderBy('key')->get()->groupBy('group');

        return view('admin.settings.index', ['groups' => $groups]);
    }

    public function update(Request $request)
    {
        $values = $request->input('settings', []);

        // Only ever write to keys that already exist in the table — this
        // is an editor for existing settings, not a way to inject new ones.
        $settings = Setting::whereIn('key', array_keys($values))->get();

        foreach ($settings as $setting) {
            $value = $values[$setting->key];

            // A blank password-style field means "leave the secret as is".
            if ($setting->is_secret && $value === '') {
                continue;
            }

            $setting->update(['value' => $value]);
        }

        Cache::forget('settings.all');

        return back()->with('status', 'Settings updated.');
    }
}
