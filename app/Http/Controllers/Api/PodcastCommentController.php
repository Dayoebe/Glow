<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast\Comment;
use App\Models\Podcast\Episode;
use Illuminate\Http\Request;

class PodcastCommentController extends Controller
{
    public function index(string $showSlug, string $episodeSlug)
    {
        $episode = Episode::published()
            ->where('slug', $episodeSlug)
            ->whereHas('show', function ($query) use ($showSlug) {
                $query->where('slug', $showSlug);
            })
            ->firstOrFail();

        $comments = Comment::with(['user', 'replies.user'])
            ->where('episode_id', $episode->id)
            ->where('is_approved', true)
            ->whereNull('parent_id')
            ->latest()
            ->get()
            ->map(fn ($comment) => $this->formatComment($comment))
            ->values();

        return response()->json([
            'data' => $comments,
        ]);
    }

    public function store(Request $request, string $showSlug, string $episodeSlug)
    {
        $episode = Episode::published()
            ->where('slug', $episodeSlug)
            ->whereHas('show', function ($query) use ($showSlug) {
                $query->where('slug', $showSlug);
            })
            ->firstOrFail();

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:180'],
            'timestamp' => ['nullable', 'integer', 'min:0'],
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parentExists = Comment::where('id', $parentId)
                ->where('episode_id', $episode->id)
                ->exists();
            if (!$parentExists) {
                return response()->json(['message' => 'Invalid parent comment.'], 422);
            }
        }

        $comment = Comment::create([
            'episode_id' => $episode->id,
            'parent_id' => $parentId,
            'user_id' => $request->user()?->id,
            'author_name' => $data['author_name'] ?? null,
            'author_email' => $data['author_email'] ?? null,
            'comment' => $data['comment'],
            'timestamp' => $data['timestamp'] ?? null,
            'is_approved' => true,
        ]);

        return response()->json([
            'data' => $this->formatComment($comment->fresh(['user', 'replies.user'])),
        ]);
    }

    public function like(string $showSlug, string $episodeSlug, int $commentId)
    {
        $episode = Episode::published()
            ->where('slug', $episodeSlug)
            ->whereHas('show', function ($query) use ($showSlug) {
                $query->where('slug', $showSlug);
            })
            ->firstOrFail();

        $comment = Comment::where('episode_id', $episode->id)
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

    private function formatComment(Comment $comment): array
    {
        $authorName = $comment->author_name
            ?? $comment->user?->name
            ?? 'Anonymous';

        return [
            'id' => $comment->id,
            'comment' => $comment->comment,
            'timestamp' => $comment->timestamp,
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
