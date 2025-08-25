@extends('layouts.admin')

@section('content')
<div x-data="{ showSuccess: false }" class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Change Password</h1>
        <a href="{{ route('admin.profile') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 text-sm">Back to Profile</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-rose-200">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow ring-1 ring-black/5 p-6">
        <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input type="password" name="current_password" required class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
                @error('current_password')
                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password" required minlength="8" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
                @error('password')
                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input type="password" name="password_confirmation" required minlength="8" class="block w-full rounded-md border-gray-300 focus:ring-primary-600 focus:border-primary-600" />
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-700 text-white hover:bg-primary-800">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    @if (session('password_updated'))
        <div x-init="showSuccess = true; setTimeout(() => showSuccess = false, 2000)" class="hidden"></div>
    @endif

    <!-- Success Popup -->
    <div x-show="showSuccess" x-transition.opacity x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative bg-white rounded-xl shadow-xl p-6 w-full max-w-sm text-center">
            <div class="mx-auto h-16 w-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                <svg class="h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Done</h3>
            <p class="text-gray-600 mt-1">Password updated successfully.</p>
            <button @click="showSuccess = false" class="mt-4 inline-flex items-center px-4 py-2 rounded-md bg-primary-700 text-white hover:bg-primary-800">Close</button>
        </div>
    </div>
</div>
@endsection
