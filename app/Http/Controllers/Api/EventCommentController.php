<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventComment;
use Illuminate\Http\Request;

class EventCommentController extends Controller
{
    public function index(string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $comments = EventComment::with(['user', 'replies.user'])
            ->where('event_id', $event->id)
            ->approved()
            ->parentOnly()
            ->latest()
            ->get()
            ->map(fn ($comment) => $this->formatComment($comment))
            ->values();

        return response()->json([
            'data' => $comments,
        ]);
    }

    public function store(Request $request, string $slug)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        if (!$event->allow_comments) {
            return response()->json(['message' => 'Comments are disabled for this event.'], 403);
        }

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:180'],
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parentExists = EventComment::where('id', $parentId)
                ->where('event_id', $event->id)
                ->exists();
            if (!$parentExists) {
                return response()->json(['message' => 'Invalid parent comment.'], 422);
            }
        }

        $comment = EventComment::create([
            'event_id' => $event->id,
            'user_id' => $request->user()?->id,
            'parent_id' => $parentId,
            'comment' => $data['comment'],
            'author_name' => $data['author_name'] ?? null,
            'author_email' => $data['author_email'] ?? null,
            'is_approved' => true,
        ]);

        return response()->json([
            'data' => $this->formatComment($comment->fresh(['user'])),
        ]);
    }

    public function like(string $slug, int $commentId)
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $comment = EventComment::where('event_id', $event->id)
            ->where('id', $commentId)
            ->firstOrFail();

        $comment->increment('likes');

        return response()->json([
            'data' => [
                'id' => $comment->id,
                'likes' => $comment->likes,
            ],
        ]);
    }

    private function formatComment(EventComment $comment): array
    {
        $authorName = $comment->author_name
            ?? $comment->user?->name
            ?? 'Anonymous';

        return [
            'id' => $comment->id,
            'comment' => $comment->comment,
            'likes' => $comment->likes ?? 0,
            'author' => [
                'name' => $authorName,
                'email' => $comment->author_email,
                'avatar' => $comment->user?->avatar
                    ?? 'https://ui-avatars.com/api/?name=' . urlencode($authorName),
            ],
            'created_at' => $comment->created_at?->toDateTimeString(),
            'created_human' => $comment->created_at?->diffForHumans(),
            'replies' => $comment->replies
                ->where('is_approved', true)
                ->sortByDesc('created_at')
                ->values()
                ->map(fn ($reply) => $this->formatComment($reply)),
        ];
    }
}
