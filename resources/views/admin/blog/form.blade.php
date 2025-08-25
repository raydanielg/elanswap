@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">{{ $mode === 'create' ? 'Create Post' : 'Edit Post' }}</h1>
        <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 text-sm">Back</a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-rose-200">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $mode==='create' ? route('admin.blog.store') : route('admin.blog.update', $post) }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow ring-1 ring-black/5 p-4 space-y-4">
        @csrf
        @if($mode==='edit') @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <input name="title" value="{{ old('title', $post->title) }}" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Published at</label>
                <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured ?? false)) class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                <label class="text-sm text-gray-700">Featured</label>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Meta title</label>
                <input name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Meta description</label>
                <input name="meta_description" value="{{ old('meta_description', $post->meta_description) }}" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Excerpt</label>
            <textarea name="excerpt" rows="2" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Body</label>
            <textarea name="body" rows="10" class="mt-1 w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500">{{ old('body', $post->body) }}</textarea>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Cover image</label>
                <input type="file" name="image" accept="image/*" class="mt-1 w-full text-sm" />
                @if($post->image_path)
                    <img src="{{ asset('storage/'.$post->image_path) }}" class="mt-2 h-24 w-24 rounded object-cover ring-1 ring-black/10" />
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tags</label>
                <div class="mt-1 space-y-2">
                    <select name="tags[]" multiple class="w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        @foreach($allTags as $t)
                            <option value="{{ $t->id }}" @selected(collect(old('tags', $post->tags->pluck('id')->all()))->contains($t->id))>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <input name="tags[]" placeholder="Add tags, comma separated" class="w-full rounded-md border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
                </div>
            </div>
        </div>

        <div class="pt-2">
            <button class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white text-sm hover:bg-primary-700">{{ $mode==='create' ? 'Create' : 'Save changes' }}</button>
        </div>
    </form>
</div>
@endsection
