@extends('layouts.admin')

@section('content')
@php($header = 'Settings · General')
<div class="max-w-4xl mx-auto" x-data="{ saved: {{ session('status') ? 'true' : 'false' }} }" x-init="if (saved) { setTimeout(() => saved = false, 2000) }">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">General Settings</h1>
        <div x-show="saved" x-transition.opacity class="inline-flex items-center px-3 py-1.5 rounded-md bg-green-100 text-green-800 text-sm">
            <svg class="h-5 w-5 mr-1.5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Saved
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-rose-200">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data" class="space-y-8 bg-white p-6 rounded-lg shadow ring-1 ring-black/5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $settings->tagline) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" name="contact_address" value="{{ old('contact_address', $settings->contact_address) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                <div class="flex items-center space-x-4">
                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-700" />
                    @if ($settings->logo_path)
                        <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Logo" class="h-10 w-auto rounded border" />
                    @endif
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                <div class="flex items-center space-x-4">
                    <input type="file" name="favicon" accept="image/*" class="block w-full text-sm text-gray-700" />
                    @if ($settings->favicon_path)
                        <img src="{{ asset('storage/'.$settings->favicon_path) }}" alt="Favicon" class="h-10 w-10 rounded border" />
                    @endif
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Social Links</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Facebook</label>
                    <input type="url" name="facebook" value="{{ old('facebook', $settings->social_links['facebook'] ?? '') }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Twitter</label>
                    <input type="url" name="twitter" value="{{ old('twitter', $settings->social_links['twitter'] ?? '') }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Instagram</label>
                    <input type="url" name="instagram" value="{{ old('instagram', $settings->social_links['instagram'] ?? '') }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-700 text-white hover:bg-primary-800">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
