<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use Illuminate\Http\Request;

class EventEngagementController extends Controller
{
    public function summary(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatSummary($event, $request->user()?->id),
        ]);
    }

    public function react(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'type' => ['required', 'string', 'in:love,insightful,fire,wow'],
        ]);

        $toggled = $event->toggleReaction($request->user()?->id, $data['type']);

        return response()->json([
            'data' => array_merge($this->formatSummary($event->fresh(), $request->user()?->id), [
                'toggled' => $toggled,
                'type' => $data['type'],
            ]),
        ]);
    }

    public function bookmark(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'collection' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $bookmarked = $event->toggleBookmark(
            $request->user()?->id,
            $data['collection'] ?? null,
            $data['notes'] ?? null
        );

        return response()->json([
            'data' => array_merge($this->formatSummary($event->fresh(), $request->user()?->id), [
                'bookmarked' => $bookmarked,
            ]),
        ]);
    }

    public function share(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'platform' => ['nullable', 'string', 'max:40'],
        ]);

        $event->trackShare($data['platform'] ?? 'share');

        return response()->json([
            'data' => $this->formatSummary($event->fresh(), $request->user()?->id),
        ]);
    }

    public function view(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $event->incrementViews($request->ip(), $request->user()?->id);

        return response()->json([
            'data' => [
                'views' => $event->views,
            ],
        ]);
    }

    private function formatSummary(Event $event, ?int $userId): array
    {
        return [
            'views' => $event->views,
            'shares' => $event->shares,
            'reactions' => $event->getAllReactionCounts(),
            'bookmarked' => $event->isBookmarkedBy($userId),
            'user_reactions' => $userId ? [
                'love' => $event->hasReaction($userId, 'love'),
                'insightful' => $event->hasReaction($userId, 'insightful'),
                'fire' => $event->hasReaction($userId, 'fire'),
                'wow' => $event->hasReaction($userId, 'wow'),
            ] : [],
        ];
    }
}
