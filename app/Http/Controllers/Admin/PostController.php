<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user','tags'])->orderByDesc('published_at')->orderByDesc('created_at');
        if ($search = $request->string('q')->toString()) {
            $query->where(function($q) use ($search){
                $q->where('title','like',"%{$search}%")
                  ->orWhere('excerpt','like',"%{$search}%");
            });
        }
        $posts = $query->paginate(12)->withQueryString();
        $q = $search; // for the blade
        return view('admin.blog.index', compact('posts', 'q'));
    }

    public function create()
    {
        $post = new Post();
        $allTags = Tag::orderBy('name')->get();
        return view('admin.blog.form', [
            'post' => $post,
            'allTags' => $allTags,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['user_id'] = Auth::id();
        $data['slug'] = $this->uniqueSlug($data['title']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('posts', 'public');
        }

        if (empty($data['excerpt'])) {
            $data['excerpt'] = Str::limit(strip_tags($data['body']), 160);
        }

        $post = Post::create($data);
        $this->syncTags($post, $request->input('tags', []));

        return redirect()->route('admin.blog.index')->with('status', 'Post created');
    }

    public function edit(Post $post)
    {
        $post->load('tags');
        $allTags = Tag::orderBy('name')->get();
        return view('admin.blog.form', [
            'post' => $post,
            'allTags' => $allTags,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $this->validateData($request, $post->id);
        $data['is_featured'] = $request->boolean('is_featured');
        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $data['image_path'] = $request->file('image')->store('posts', 'public');
        }
        if (empty($data['excerpt'])) {
            $data['excerpt'] = Str::limit(strip_tags($data['body']), 160);
        }
        // Keep existing slug unless title changed and no custom slug provided
        if ($post->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $post->id);
        }

        $post->update($data);
        $this->syncTags($post, $request->input('tags', []));

        return redirect()->route('admin.blog.index')->with('status', 'Post updated');
    }

    public function destroy(Post $post)
    {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        $post->tags()->detach();
        $post->delete();
        return back()->with('status', 'Post deleted');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:200',
            'excerpt' => 'nullable|string|max:300',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (Post::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id','!=',$ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }

    private function syncTags(Post $post, array $tagsInput): void
    {
        // tagsInput can be array of tag IDs or comma-separated new tag names
        $tagIds = [];
        foreach ($tagsInput as $item) {
            if (is_numeric($item)) {
                $tagIds[] = (int)$item;
                continue;
            }
            // Split comma-separated strings
            $parts = array_filter(array_map('trim', preg_split('/[,]+/', (string)$item)));
            foreach ($parts as $name) {
                if ($name === '') { continue; }
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $tagIds[] = $tag->id;
            }
        }
        $post->tags()->sync($tagIds);
    }
}
