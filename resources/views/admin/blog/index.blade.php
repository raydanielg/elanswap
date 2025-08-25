@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Blog Posts</h1>
        <a href="{{ route('admin.blog.create') }}" class="px-3 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold text-sm">New Post</a>
    </div>

    <form method="GET" class="mb-4 flex gap-3">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search..." class="border rounded px-3 py-2 w-80">
        <button class="px-3 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold text-sm">Search</button>
    </form>

    @if (session('status'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">{{ session('status') }}</div>
    @endif

    <div class="overflow-x-auto bg-white border rounded">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-sm text-gray-600">
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Slug</th>
                    <th class="px-4 py-2">Published</th>
                    <th class="px-4 py-2">Updated</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($posts as $post)
                <tr class="border-t">
                    <td class="px-4 py-2 font-medium">{{ $post->title }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $post->slug }}</td>
                    <td class="px-4 py-2">{{ $post->published_at ? $post->published_at->format('Y-m-d') : '—' }}</td>
                    <td class="px-4 py-2">{{ $post->updated_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('admin.blog.edit', $post) }}" class="text-blue-700 hover:underline mr-3">Edit</a>
                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-700 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No posts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $posts->links() }}</div>
</div>
@endsection
