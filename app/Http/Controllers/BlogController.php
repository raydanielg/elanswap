<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tag = trim((string) $request->query('tag', ''));
        $perPage = (int) $request->query('per_page', 9);
        $perPage = $perPage > 0 && $perPage <= 48 ? $perPage : 9;

        $query = Post::query()->with('tags')->latest('published_at')->latest('created_at');
        // Show only published posts by default
        $query->whereNotNull('published_at');

        if ($q !== '') {
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%$q%")
                    ->orWhere('excerpt', 'like', "%$q%")
                    ->orWhere('body', 'like', "%$q%");
            });
        }
        if ($tag !== '') {
            $query->whereHas('tags', function($t) use ($tag) {
                $t->where('slug', $tag);
            });
        }

        $posts = $query->paginate($perPage)->withQueryString();

        $tags = Tag::query()
            ->withCount(['posts' => function($p){ $p->whereNotNull('published_at'); }])
            ->orderBy('name')
            ->get();

        return view('blog.index', compact('posts','tags','q','tag'));
    }

    public function show(string $slug)
    {
        $post = Post::with('tags', 'user')->where('slug', $slug)->firstOrFail();
        return view('blog.show', compact('post'));
    }
}
