<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MobileController extends Controller
{
    public function edit(): View
    {
        return view('admin.mobile', [
            'values' => [
                'pwa_enabled' => Setting::getBool('pwa_enabled', true),
                'pwa_short_name' => Setting::get('pwa_short_name', config('app.name')),
                'pwa_theme_color' => Setting::get('pwa_theme_color', '#4f46e5'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'pwa_enabled' => ['nullable', 'boolean'],
            'pwa_short_name' => ['required', 'string', 'max:30'],
            'pwa_theme_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        Setting::set('pwa_enabled', $request->boolean('pwa_enabled'));
        Setting::set('pwa_short_name', $data['pwa_short_name']);
        Setting::set('pwa_theme_color', $data['pwa_theme_color']);

        return back()->with('status', 'mobile-settings-updated');
    }
}
