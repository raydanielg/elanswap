@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Blog</h1>
        <p class="mt-1 text-gray-600">News, tips and updates from ElanSwap</p>
    </div>

    <form method="GET" action="{{ route('blog.index') }}" class="mb-6 flex flex-col sm:flex-row gap-3 items-stretch sm:items-end">
        <div class="flex-1">
            <label class="block text-sm text-gray-700 mb-1">Search</label>
            <input type="text" name="q" value="{{ $q ?? '' }}" class="w-full border rounded-md px-3 py-2" placeholder="Search posts...">
        </div>
        <div>
            <label class="block text-sm text-gray-700 mb-1">Tag</label>
            <select name="tag" class="border rounded-md px-3 py-2 min-w-[200px]">
                <option value="">All</option>
                @foreach(($tags ?? []) as $t)
                    <option value="{{ $t->slug }}" @selected(($tag ?? '') === $t->slug)>{{ $t->name }} ({{ $t->posts_count }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="px-4 py-2 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 font-semibold">Filter</button>
        </div>
    </form>

    @if(($posts->count() ?? 0) === 0)
        <div class="min-h-[50vh] py-16 flex flex-col items-center justify-center text-center">
            <img src="{{ asset('assets/empty-blog.svg') }}" class="w-auto max-w-[16rem] h-40 mx-auto" alt="No posts" />
            <p class="mt-4 text-gray-700 text-sm sm:text-base">No blog posts — stay tuned</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                <article class="bg-white border rounded-lg shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                    @if($post->image_path)
                        <img src="{{ asset('storage/'.$post->image_path) }}" alt="{{ $post->title }}" class="h-40 w-full object-cover">
                    @else
                        <div class="h-40 bg-gradient-to-r from-blue-100 to-blue-200"></div>
                    @endif
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="text-xs text-gray-500">{{ optional($post->published_at ?? $post->created_at)->format('M d, Y') }}</div>
                        <h2 class="mt-1 text-lg font-semibold text-gray-900">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 flex-1">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 120) }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($post->tags as $tg)
                                <a href="{{ route('blog.index', ['tag'=>$tg->slug]) }}" class="px-2 py-0.5 text-xs rounded bg-blue-50 text-blue-700 border border-blue-200">#{{ $tg->name }}</a>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 text-sm font-semibold">Read more</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
