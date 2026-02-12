<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Models\News\News;
use App\Models\Podcast\Subscription;
use App\Models\Podcast\Show;
use Illuminate\Http\Request;

class UserLibraryController extends Controller
{
    public function bookmarks(Request $request)
    {
        $userId = $request->user()?->id;
        $type = $request->query('type', 'news');

        if ($type === 'blog') {
            $items = Post::with(['category', 'author'])
                ->published()
                ->whereHas('interactions', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->where('type', 'bookmark');
                })
                ->latest('published_at')
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'slug' => $post->slug,
                        'title' => $post->title,
                        'excerpt' => $post->excerpt,
                        'image' => $post->featured_image,
                        'category' => $post->category?->name,
                        'published_at' => $post->published_at?->format('Y-m-d H:i:s'),
                        'read_time' => $post->read_time,
                        'views' => $post->views,
                        'comments_count' => $post->comments_count,
                    ];
                })
                ->values();

            return response()->json([
                'data' => $items,
                'meta' => ['type' => 'blog'],
            ]);
        }

        $items = News::with(['category', 'author'])
            ->published()
            ->whereHas('interactions', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('type', 'bookmark');
            })
            ->latest('published_at')
            ->get()
            ->map(function ($news) {
                return [
                    'id' => $news->id,
                    'slug' => $news->slug,
                    'title' => $news->title,
                    'excerpt' => $news->excerpt,
                    'image' => $news->featured_image,
                    'category' => $news->category?->name,
                    'published_at' => $news->published_at?->format('Y-m-d H:i:s'),
                    'read_time' => $news->read_time,
                    'views' => $news->views,
                    'comments_count' => $news->comments_count,
                ];
            })
            ->values();

        return response()->json([
            'data' => $items,
            'meta' => ['type' => 'news'],
        ]);
    }

    public function subscriptions(Request $request)
    {
        $userId = $request->user()?->id;

        $subscriptions = Subscription::with('show')
            ->where('user_id', $userId)
            ->get();

        $shows = $subscriptions
            ->filter(fn ($subscription) => $subscription->show)
            ->map(function ($subscription) {
                $show = $subscription->show;
                return [
                    'id' => $show->id,
                    'slug' => $show->slug,
                    'title' => $show->title,
                    'description' => $show->description,
                    'cover_image' => $show->cover_image,
                    'category' => $show->category,
                    'host_name' => $show->host_name,
                    'total_episodes' => $show->total_episodes,
                    'subscribers' => $show->subscribers,
                    'notifications_enabled' => $subscription->notifications_enabled,
                    'subscribed_at' => $subscription->subscribed_at?->format('Y-m-d H:i:s'),
                ];
            })
            ->values();

        return response()->json([
            'data' => $shows,
        ]);
    }
}
