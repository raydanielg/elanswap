@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Blog Posts</h1>
        <a href="{{ route('admin.blog.create') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-primary-600 text-white text-sm hover:bg-primary-700">New Post</a>
    </div>

    <form method="GET" class="mb-4 flex gap-3">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search..." class="rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500 px-3 py-2 w-80">
        <button class="px-3 py-2 rounded-md bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 text-sm">Search</button>
    </form>

    @if (session('status'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">{{ session('status') }}</div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow ring-1 ring-black/5">
        <table class="min-w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-sm text-gray-600">
                    <th class="px-4 py-2">Post</th>
                    <th class="px-4 py-2">Slug</th>
                    <th class="px-4 py-2">Published</th>
                    <th class="px-4 py-2">Updated</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($posts as $post)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-3">
                            @if($post->image_path)
                                <img src="{{ asset('storage/'.$post->image_path) }}" class="h-10 w-10 rounded object-cover ring-1 ring-black/10" alt=""/>
                            @else
                                <div class="h-10 w-10 rounded bg-gray-100 ring-1 ring-black/10"></div>
                            @endif
                            <div>
                                <div class="font-medium flex items-center gap-2">
                                    {{ $post->title }}
                                    @if($post->is_featured)
                                        <span class="text-amber-700 bg-amber-100 border border-amber-200 text-[10px] px-1.5 py-0.5 rounded">Featured</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">By {{ $post->user->name ?? '—' }} • {{ $post->tags->pluck('name')->join(', ') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-gray-600">{{ $post->slug }}</td>
                    <td class="px-4 py-2">{{ $post->published_at ? $post->published_at->format('Y-m-d') : '—' }}</td>
                    <td class="px-4 py-2">{{ $post->updated_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('admin.blog.edit', $post) }}" class="inline-flex items-center px-2 py-1.5 rounded text-blue-700 hover:bg-blue-50">Edit</a>
                        <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button class="inline-flex items-center px-2 py-1.5 rounded text-rose-700 hover:bg-rose-50">Delete</button>
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
