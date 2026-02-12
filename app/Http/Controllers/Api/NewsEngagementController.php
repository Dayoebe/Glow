<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use Illuminate\Http\Request;

class NewsEngagementController extends Controller
{
    public function summary(Request $request, string $slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatSummary($news, $request->user()?->id),
        ]);
    }

    public function react(Request $request, string $slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'type' => ['required', 'string', 'in:love,insightful,fire,wow'],
        ]);

        $toggled = $news->toggleReaction($request->user()?->id, $data['type']);

        return response()->json([
            'data' => array_merge($this->formatSummary($news->fresh(), $request->user()?->id), [
                'toggled' => $toggled,
                'type' => $data['type'],
            ]),
        ]);
    }

    public function bookmark(Request $request, string $slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'collection' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $bookmarked = $news->toggleBookmark(
            $request->user()?->id,
            $data['collection'] ?? null,
            $data['notes'] ?? null
        );

        return response()->json([
            'data' => array_merge($this->formatSummary($news->fresh(), $request->user()?->id), [
                'bookmarked' => $bookmarked,
            ]),
        ]);
    }

    public function share(Request $request, string $slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'platform' => ['nullable', 'string', 'max:40'],
        ]);

        $news->trackShare($data['platform'] ?? 'share');

        return response()->json([
            'data' => $this->formatSummary($news->fresh(), $request->user()?->id),
        ]);
    }

    public function view(Request $request, string $slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $news->incrementViews($request->ip(), $request->user()?->id);

        return response()->json([
            'data' => [
                'views' => $news->views,
            ],
        ]);
    }

    private function formatSummary(News $news, ?int $userId): array
    {
        return [
            'views' => $news->views,
            'shares' => $news->shares,
            'reactions' => $news->getAllReactionCounts(),
            'bookmarked' => $news->isBookmarkedBy($userId),
            'user_reactions' => $userId ? [
                'love' => $news->hasReaction($userId, 'love'),
                'insightful' => $news->hasReaction($userId, 'insightful'),
                'fire' => $news->hasReaction($userId, 'fire'),
                'wow' => $news->hasReaction($userId, 'wow'),
            ] : [],
        ];
    }
}
