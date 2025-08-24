@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Announcement</h1>
        <p class="text-gray-600">Update the announcement details.</p>
    </div>

    <form action="{{ route('admin.features.update', $feature) }}" method="POST" class="space-y-4 bg-white p-6 rounded-lg border border-gray-200">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input name="title" type="text" value="{{ old('title', $feature->title) }}" class="mt-1 w-full border rounded px-3 py-2" required>
            @error('title')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="4" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $feature->description) }}</textarea>
            @error('description')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Icon</label>
                <input name="icon" type="text" value="{{ old('icon', $feature->icon) }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="e.g., bolt, shield, map">
                @error('icon')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                <input name="sort_order" type="number" value="{{ old('sort_order', $feature->sort_order) }}" class="mt-1 w-full border rounded px-3 py-2" min="0">
                @error('sort_order')<div class="text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $feature->is_active) ? 'checked' : '' }} class="border rounded">
            <label for="is_active" class="text-sm text-gray-700">Publish (active)</label>
        </div>
        <div class="flex items-center space-x-3">
            <button class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700" type="submit">Save</button>
            <a href="{{ route('admin.features.index') }}" class="px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
</div>
@endsection
