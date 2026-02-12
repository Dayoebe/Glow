<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));
        $sortBy = $request->query('sort', 'latest');
        $perPage = (int) $request->query('per_page', 9);
        $perPage = min(24, max(6, $perPage));

        $query = Post::with(['category', 'author'])
            ->published();

        if ($category !== 'all') {
            $query->byCategory($category);
        }

        if ($search !== '') {
            $query->search($search);
        }

        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'trending':
                $query->trending(7);
                break;
            default:
                $query->latest('published_at');
        }

        $posts = $query->paginate($perPage);

        $data = $posts->getCollection()
            ->map(fn ($item) => $this->formatPostItem($item))
            ->values();

        $featuredPost = Post::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->first();

        $trendingPosts = Post::with(['category'])
            ->published()
            ->trending(7)
            ->take(5)
            ->get()
            ->map(fn ($item) => $this->formatPostItem($item))
            ->values();

        $categories = Category::active()
            ->withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->get()
            ->map(function ($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->name,
                    'count' => $cat->posts_count,
                    'color' => $cat->color,
                ];
            })
            ->prepend([
                'slug' => 'all',
                'name' => 'All Posts',
                'count' => Post::published()->count(),
                'color' => 'emerald',
            ])
            ->values();

        $popularTags = Post::published()
            ->whereNotNull('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(15)
            ->keys()
            ->toArray();

        return response()->json([
            'data' => $data,
            'meta' => [
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
                'featuredPost' => $featuredPost ? $this->formatPostItem($featuredPost) : null,
                'trendingPosts' => $trendingPosts,
                'categories' => $categories,
                'popularTags' => $popularTags,
            ],
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $post->incrementRawView();

        $related = Post::with(['category', 'author'])
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(fn ($item) => $this->formatPostItem($item));

        $seriesPosts = collect();
        if ($post->series) {
            $seriesPosts = Post::published()
                ->where('series', $post->series)
                ->orderBy('series_order')
                ->get()
                ->map(fn ($item) => $this->formatPostItem($item));
        }

        return response()->json([
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'featured_image' => $post->featured_image,
                'gallery' => $post->gallery,
                'category' => $post->category ? [
                    'name' => $post->category->name,
                    'slug' => $post->category->slug,
                    'color' => $post->category->color,
                ] : null,
                'author' => $post->author ? [
                    'id' => $post->author->id,
                    'name' => $post->author->name,
                    'avatar' => $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name),
                    'role' => $post->author->role_label ?? 'Author',
                ] : null,
                'published_at' => $post->published_at?->format('Y-m-d H:i:s'),
                'read_time' => $post->read_time,
                'views' => $post->views,
                'shares' => $post->shares,
                'tags' => $post->tags,
                'series' => $post->series,
                'allow_comments' => $post->allow_comments,
            ],
            'related' => $related,
            'series' => $seriesPosts,
        ]);
    }

    private function formatPostItem(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'featured_image' => $post->featured_image,
            'category' => $post->category ? [
                'name' => $post->category->name,
                'slug' => $post->category->slug,
                'color' => $post->category->color,
            ] : null,
            'author' => $post->author ? [
                'id' => $post->author->id,
                'name' => $post->author->name,
                'avatar' => $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name),
                'role' => $post->author->role_label ?? 'Author',
            ] : null,
            'published_at' => $post->published_at?->format('Y-m-d H:i:s'),
            'read_time' => $post->read_time,
            'views' => $post->views,
            'shares' => $post->shares,
            'comments_count' => $post->comments_count,
        ];
    }
}
