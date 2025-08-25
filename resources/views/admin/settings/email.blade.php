@extends('layouts.admin')

@section('content')
@php($header = 'Settings · Email')
<div class="max-w-3xl mx-auto" x-data="{ saved: {{ session('status') ? 'true' : 'false' }} }" x-init="if (saved) { setTimeout(() => saved = false, 2000) }">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Email Settings</h1>
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

    <form method="POST" action="{{ route('admin.settings.email.update') }}" class="space-y-8 bg-white p-6 rounded-lg shadow ring-1 ring-black/5">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings->mail_from_name) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings->mail_from_address) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="text" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password) }}" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                <select name="smtp_encryption" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600">
                    @php($enc = old('smtp_encryption', $settings->smtp_encryption))
                    <option value="none" {{ ($enc === 'none' || $enc === null) ? 'selected' : '' }}>None</option>
                    <option value="ssl" {{ $enc === 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="tls" {{ $enc === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="starttls" {{ $enc === 'starttls' ? 'selected' : '' }}>STARTTLS</option>
                </select>
            </div>
        </div>
        <div>
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-700 text-white hover:bg-primary-800">Save Email Settings</button>
        </div>
    </form>
</div>
@endsection
