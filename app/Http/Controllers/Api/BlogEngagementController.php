<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use Illuminate\Http\Request;

class BlogEngagementController extends Controller
{
    public function summary(Request $request, string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatSummary($post, $request->user()?->id),
        ]);
    }

    public function react(Request $request, string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'type' => ['required', 'string', 'in:love,insightful,fire,clap'],
        ]);

        $toggled = $post->toggleReaction($request->user()?->id, $data['type']);

        return response()->json([
            'data' => array_merge($this->formatSummary($post->fresh(), $request->user()?->id), [
                'toggled' => $toggled,
                'type' => $data['type'],
            ]),
        ]);
    }

    public function bookmark(Request $request, string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'collection' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $bookmarked = $post->toggleBookmark(
            $request->user()?->id,
            $data['collection'] ?? null,
            $data['notes'] ?? null
        );

        return response()->json([
            'data' => array_merge($this->formatSummary($post->fresh(), $request->user()?->id), [
                'bookmarked' => $bookmarked,
            ]),
        ]);
    }

    public function share(Request $request, string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'platform' => ['nullable', 'string', 'max:40'],
        ]);

        $post->trackShare($data['platform'] ?? 'share');

        return response()->json([
            'data' => $this->formatSummary($post->fresh(), $request->user()?->id),
        ]);
    }

    public function view(Request $request, string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $post->incrementViews($request->ip(), $request->user()?->id);

        return response()->json([
            'data' => [
                'views' => $post->views,
            ],
        ]);
    }

    private function formatSummary(Post $post, ?int $userId): array
    {
        return [
            'views' => $post->views,
            'shares' => $post->shares,
            'reactions' => $post->getAllReactionCounts(),
            'bookmarked' => $post->isBookmarkedBy($userId),
            'user_reactions' => $userId ? [
                'love' => $post->hasReaction($userId, 'love'),
                'insightful' => $post->hasReaction($userId, 'insightful'),
                'fire' => $post->hasReaction($userId, 'fire'),
                'clap' => $post->hasReaction($userId, 'clap'),
            ] : [],
        ];
    }
}
