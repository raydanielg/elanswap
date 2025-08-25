<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // General settings form
    public function general()
    {
        $settings = Setting::first();
        if (! $settings) {
            $settings = Setting::create([]);
        }
        return view('admin.settings.general', compact('settings'));
    }

    // Save general settings
    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
        ]);

        $settings = Setting::first() ?: Setting::create([]);

        // Handle files
        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $path = $request->file('logo')->store('branding', 'public');
            $settings->logo_path = $path;
        }
        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $path = $request->file('favicon')->store('branding', 'public');
            $settings->favicon_path = $path;
        }

        // Social links
        $social = [
            'facebook' => $request->input('facebook'),
            'twitter' => $request->input('twitter'),
            'instagram' => $request->input('instagram'),
        ];
        $settings->social_links = $social;

        // Scalars
        $settings->site_name = $data['site_name'];
        $settings->tagline = $data['tagline'] ?? null;
        $settings->contact_email = $data['contact_email'] ?? null;
        $settings->contact_phone = $data['contact_phone'] ?? null;
        $settings->contact_address = $data['contact_address'] ?? null;

        $settings->save();

        return back()->with('status', 'Settings saved.');
    }

    // Branding stub
    public function branding()
    {
        $settings = Setting::first();
        return view('admin.settings.branding', compact('settings'));
    }

    // Email settings stub
    public function email()
    {
        $settings = Setting::first();
        return view('admin.settings.email', compact('settings'));
    }

    // Save email settings
    public function updateEmail(Request $request)
    {
        $data = $request->validate([
            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email|max:255',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|in:none,ssl,tls,starttls',
        ]);

        $settings = Setting::first() ?: Setting::create([]);
        $settings->fill($data);
        $settings->save();

        return back()->with('status', 'Email settings saved.');
    }
}
