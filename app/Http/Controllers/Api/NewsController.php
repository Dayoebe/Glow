<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Support\RichTextSanitizer;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(private readonly RichTextSanitizer $richTextSanitizer)
    {
    }

    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));
        $tag = trim((string) $request->query('tag', ''));
        $sortBy = $request->query('sort', 'latest');
        $perPage = (int) $request->query('per_page', 9);
        $perPage = min(24, max(6, $perPage));

        $query = News::with(['category', 'author'])
            ->published();

        if ($category !== 'all') {
            $query->byCategory($category);
        }

        if ($search !== '') {
            $query->search($search);
        }

        if ($tag !== '') {
            $query->whereJsonContains('tags', $tag);
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

        $news = $query->paginate($perPage);

        $data = $news->getCollection()
            ->map(fn ($item) => $this->formatNewsItem($item))
            ->values();

        $featuredHero = $this->getFeaturedHero();
        $featuredSecondary = $this->getFeaturedSecondary($featuredHero);
        $featuredSidebar = $this->getFeaturedSidebar($featuredHero, $featuredSecondary);

        $breakingNews = News::with(['category', 'author'])
            ->published()
            ->breaking()
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(fn ($item) => $this->formatNewsItem($item));

        $trendingNews = News::published()
            ->trending(7)
            ->take(5)
            ->get()
            ->map(fn ($item) => $this->formatNewsItem($item));

        $categories = NewsCategory::active()
            ->withCount(['news' => function ($query) {
                $query->published();
            }])
            ->get()
            ->map(function ($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->name,
                    'count' => $cat->news_count,
                    'icon' => $cat->icon,
                    'color' => $cat->color,
                ];
            })
            ->prepend([
                'slug' => 'all',
                'name' => 'All News',
                'count' => News::published()->count(),
                'icon' => 'fas fa-newspaper',
                'color' => 'emerald',
            ])
            ->values();

        $popularTags = News::published()
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
                    'current_page' => $news->currentPage(),
                    'last_page' => $news->lastPage(),
                    'per_page' => $news->perPage(),
                    'total' => $news->total(),
                ],
                'featuredHero' => $featuredHero,
                'featuredSecondary' => $featuredSecondary,
                'featuredSidebar' => $featuredSidebar,
                'breakingNews' => $breakingNews,
                'trendingNews' => $trendingNews,
                'categories' => $categories,
                'popularTags' => $popularTags,
            ],
        ]);
    }

    public function show(string $slug)
    {
        $news = News::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $news->incrementRawView();

        $related = News::published()
            ->where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(fn ($item) => $this->formatNewsItem($item));

        return response()->json([
            'data' => [
                'id' => $news->id,
                'title' => $news->title,
                'slug' => $news->slug,
                'excerpt' => $news->excerpt,
                'content' => $this->richTextSanitizer->sanitize($news->content),
                'featured_image' => $news->featured_image,
                'gallery' => $news->gallery,
                'video_url' => $news->video_url,
                'category' => $news->category ? [
                    'name' => $news->category->name,
                    'slug' => $news->category->slug,
                ] : null,
                'author' => $news->author ? [
                    'id' => $news->author->id,
                    'name' => $news->author->name,
                    'avatar' => $news->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($news->author->name),
                    'role' => $news->author->role_label ?? 'Author',
                ] : null,
                'published_at' => $news->published_at?->format('Y-m-d H:i:s'),
                'read_time' => $news->read_time,
                'views' => $news->views,
                'likes' => $news->likes,
                'shares' => $news->shares,
                'tags' => $news->tags,
            ],
            'related' => $related,
        ]);
    }

    private function formatNewsItem(News $news): array
    {
        return [
            'id' => $news->id,
            'title' => $news->title,
            'slug' => $news->slug,
            'excerpt' => $news->excerpt,
            'featured_image' => $news->featured_image,
            'category' => $news->category ? [
                'name' => $news->category->name,
                'slug' => $news->category->slug,
            ] : null,
            'author' => $news->author ? [
                'id' => $news->author->id,
                'name' => $news->author->name,
                'avatar' => $news->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($news->author->name),
                'role' => $news->author->role_label ?? 'Author',
            ] : null,
            'published_at' => $news->published_at?->format('Y-m-d H:i:s'),
            'read_time' => $news->read_time,
            'views' => $news->views,
            'likes' => $news->likes,
            'shares' => $news->shares,
            'comments_count' => $news->comments_count,
        ];
    }

    private function getFeaturedHero(): ?array
    {
        $query = News::with(['category', 'author'])
            ->published()
            ->featured();

        $hero = (clone $query)
            ->where('featured_position', 'hero')
            ->latest('published_at')
            ->first();

        if (!$hero) {
            $hero = (clone $query)->latest('published_at')->first();
        }

        return $hero ? $this->formatNewsItem($hero) : null;
    }

    private function getFeaturedSecondary(?array $featuredHero): array
    {
        $query = News::with(['category', 'author'])
            ->published()
            ->featured();

        $secondary = $query->where('featured_position', 'secondary')
            ->latest('published_at')
            ->take(2)
            ->get();

        if ($secondary->count() < 2) {
            $excludeIds = $secondary->pluck('id')->toArray();
            if ($featuredHero) {
                $excludeIds[] = $featuredHero['id'];
            }

            $fallback = News::with(['category', 'author'])
                ->published()
                ->featured()
                ->whereNotIn('id', $excludeIds)
                ->latest('published_at')
                ->take(2 - $secondary->count())
                ->get();

            $secondary = $secondary->concat($fallback);
        }

        return $secondary->map(fn ($news) => $this->formatNewsItem($news))->values()->toArray();
    }

    private function getFeaturedSidebar(?array $featuredHero, array $featuredSecondary): array
    {
        $query = News::with(['category', 'author'])
            ->published()
            ->featured();

        $sidebar = $query->where('featured_position', 'sidebar')
            ->latest('published_at')
            ->take(4)
            ->get();

        if ($sidebar->isEmpty()) {
            $excludeIds = collect($featuredSecondary)->pluck('id')->toArray();
            if ($featuredHero) {
                $excludeIds[] = $featuredHero['id'];
            }

            $sidebar = News::with(['category', 'author'])
                ->published()
                ->featured()
                ->whereNotIn('id', $excludeIds)
                ->latest('published_at')
                ->take(4)
                ->get();
        }

        return $sidebar->map(fn ($news) => $this->formatNewsItem($news))->values()->toArray();
    }
}
