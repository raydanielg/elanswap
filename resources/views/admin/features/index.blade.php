@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Announcements</h1>
        <a href="{{ route('admin.features.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">New</a>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-emerald-50 text-emerald-700 rounded">{{ session('status') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="text-left px-4 py-2">Title</th>
                    <th class="text-left px-4 py-2">Icon</th>
                    <th class="text-left px-4 py-2">Sort</th>
                    <th class="text-left px-4 py-2">Active</th>
                    <th class="text-right px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($features as $feature)
                    <tr>
                        <td class="px-4 py-2">
                            <div class="font-medium text-gray-900">{{ $feature->title }}</div>
                            <div class="text-gray-500">{{ Str::limit($feature->description, 120) }}</div>
                        </td>
                        <td class="px-4 py-2">{{ $feature->icon ?: '-' }}</td>
                        <td class="px-4 py-2">{{ $feature->sort_order }}</td>
                        <td class="px-4 py-2">
                            @if($feature->is_active)
                                <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-700 rounded">Active</span>
                            @else
                                <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <form action="{{ route('admin.features.toggle', $feature) }}" method="POST" class="inline">
                                @csrf
                                <button class="px-3 py-1 text-xs rounded border hover:bg-gray-50" type="submit">{{ $feature->is_active ? 'Unpublish' : 'Publish' }}</button>
                            </form>
                            <a href="{{ route('admin.features.edit', $feature) }}" class="px-3 py-1 text-xs rounded border hover:bg-gray-50">Edit</a>
                            <form action="{{ route('admin.features.destroy', $feature) }}" method="POST" class="inline" onsubmit="return confirm('Delete this announcement?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 text-xs rounded border text-red-600 hover:bg-red-50" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500" colspan="5">No announcements yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $features->links() }}</div>
    </div>
</div>
@endsection
