<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $theme = $request->has('admin_theme') ? 'dark' : 'light';
        $sidebar = $request->has('admin_sidebar') ? 'compact' : 'full';

        \App\Models\Setting::updateOrCreate(['key' => 'admin_theme'], ['value' => $theme]);
        \App\Models\Setting::updateOrCreate(['key' => 'admin_sidebar'], ['value' => $sidebar]);

        return back()->with('success', 'Settings updated successfully.');
    }
}
