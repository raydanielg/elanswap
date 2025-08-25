<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $posts = Post::query()
            ->when($q !== '', function($sub) use ($q) {
                $sub->where('title','like',"%$q%")
                    ->orWhere('excerpt','like',"%$q%")
                    ->orWhere('body','like',"%$q%");
            })
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();
        return view('admin.blog.index', compact('posts','q'));
    }

    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        return view('admin.blog.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'slug' => ['nullable','string','max:255','unique:posts,slug'],
            'excerpt' => ['nullable','string','max:500'],
            'body' => ['required','string'],
            'image' => ['nullable','image','max:4096'],
            'tags' => ['nullable','string'], // comma-separated
            'published' => ['nullable','boolean'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['title']);
        if (Post::where('slug',$slug)->exists()) {
            $slug .= '-'.Str::random(5);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog','public');
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'image_path' => $imagePath,
            'published_at' => $request->boolean('published') ? now() : null,
        ]);

        $this->syncTags($post, $data['tags'] ?? '');

        return redirect()->route('admin.blog.index')->with('status','Post created');
    }

    public function edit(Post $post)
    {
        $tags = Tag::orderBy('name')->get();
        $existing = $post->tags->pluck('name')->implode(', ');
        return view('admin.blog.edit', compact('post','tags','existing'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'slug' => ['required','string','max:255','unique:posts,slug,'.$post->id],
            'excerpt' => ['nullable','string','max:500'],
            'body' => ['required','string'],
            'image' => ['nullable','image','max:4096'],
            'remove_image' => ['nullable','boolean'],
            'tags' => ['nullable','string'],
            'published' => ['nullable','boolean'],
        ]);

        if ($request->boolean('remove_image') && $post->image_path) {
            Storage::disk('public')->delete($post->image_path);
            $post->image_path = null;
        }
        if ($request->hasFile('image')) {
            if ($post->image_path) Storage::disk('public')->delete($post->image_path);
            $post->image_path = $request->file('image')->store('blog','public');
        }

        $post->title = $data['title'];
        $post->slug = $data['slug'];
        $post->excerpt = $data['excerpt'] ?? null;
        $post->body = $data['body'];
        $post->published_at = $request->boolean('published') ? ($post->published_at ?? now()) : null;
        $post->save();

        $this->syncTags($post, $data['tags'] ?? '');

        return redirect()->route('admin.blog.index')->with('status','Post updated');
    }

    public function destroy(Post $post)
    {
        if ($post->image_path) Storage::disk('public')->delete($post->image_path);
        $post->delete();
        return redirect()->route('admin.blog.index')->with('status','Post deleted');
    }

    private function syncTags(Post $post, string $tagsCsv): void
    {
        $names = collect(explode(',', $tagsCsv))
            ->map(fn($s)=>trim($s))
            ->filter()
            ->unique();
        $ids = [];
        foreach ($names as $name) {
            $slug = Str::slug($name);
            $tag = Tag::firstOrCreate(['slug'=>$slug], ['name'=>$name]);
            $ids[] = $tag->id;
        }
        $post->tags()->sync($ids);
    }
}
