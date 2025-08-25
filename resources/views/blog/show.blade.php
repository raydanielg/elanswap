@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <article class="bg-white border rounded-lg shadow-sm overflow-hidden">
        @if($post->image_path)
            <img src="{{ asset('storage/'.$post->image_path) }}" alt="{{ $post->title }}" class="w-full object-cover max-h-96">
        @endif
        <div class="p-6">
            <div class="text-xs text-gray-500">{{ optional($post->published_at ?? $post->created_at)->format('M d, Y') }} • by {{ $post->user?->name ?? 'ElanSwap' }}</div>
            <h1 class="mt-1 text-2xl font-bold text-gray-900">{{ $post->title }}</h1>
            @if($post->excerpt)
                <p class="mt-2 text-gray-600">{{ $post->excerpt }}</p>
            @endif
            <div class="mt-6 prose max-w-none">
                {!! nl2br(e($post->body)) !!}
            </div>
            @if($post->tags->count())
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach($post->tags as $tg)
                        <a href="{{ route('blog.index', ['tag'=>$tg->slug]) }}" class="px-2 py-0.5 text-xs rounded bg-blue-50 text-blue-700 border border-blue-200">#{{ $tg->name }}</a>
                    @endforeach
                </div>
            @endif
            <div class="mt-8">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-black border border-blue-700 rounded-md hover:bg-blue-200 text-sm font-semibold">Back to Blog</a>
            </div>
        </div>
    </article>
</div>
@endsection
