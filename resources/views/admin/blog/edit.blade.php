@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Blog Post</h1>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-5 bg-white border rounded-lg p-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" class="w-full border rounded px-3 py-2" required>
            @error('title')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" class="w-full border rounded px-3 py-2" required>
            @error('slug')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Excerpt</label>
            <textarea name="excerpt" rows="2" class="w-full border rounded px-3 py-2">{{ old('excerpt', $post->excerpt) }}</textarea>
            @error('excerpt')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Body</label>
            <textarea name="body" rows="8" class="w-full border rounded px-3 py-2" required>{{ old('body', $post->body) }}</textarea>
            @error('body')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Current Image</label>
            @if($post->image_path)
                <img src="{{ asset('storage/'.$post->image_path) }}" alt="{{ $post->title }}" class="h-32 object-cover rounded">
                <label class="block mt-2"><input type="checkbox" name="remove_image" value="1"> <span>Remove image</span></label>
            @else
                <div class="text-sm text-gray-500">No image.</div>
            @endif
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Upload New Image</label>
            <input type="file" name="image" accept="image/*">
            @error('image')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm text-gray-700 mb-1">Tags (comma-separated)</label>
            <input type="text" name="tags" value="{{ old('tags', $existing) }}" class="w-full border rounded px-3 py-2" placeholder="e.g. updates, tips, how-to">
            @error('tags')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="flex items-center gap-3">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="published" value="1" {{ old('published', $post->published_at ? true : false) ? 'checked' : '' }}> <span>Published</span>
            </label>
        </div>

        <div class="pt-2">
            <button class="px-4 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold">Save Changes</button>
            <a href="{{ route('admin.blog.index') }}" class="ml-3 text-gray-700 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
