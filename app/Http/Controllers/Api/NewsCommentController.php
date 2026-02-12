<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News\News;
use App\Models\News\NewsComment;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    public function index(string $slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $comments = NewsComment::with(['user', 'replies.user'])
            ->where('news_id', $news->id)
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
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:180'],
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parentExists = NewsComment::where('id', $parentId)
                ->where('news_id', $news->id)
                ->exists();
            if (!$parentExists) {
                return response()->json(['message' => 'Invalid parent comment.'], 422);
            }
        }

        $comment = NewsComment::create([
            'news_id' => $news->id,
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
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $comment = NewsComment::where('news_id', $news->id)
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

    private function formatComment(NewsComment $comment): array
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
