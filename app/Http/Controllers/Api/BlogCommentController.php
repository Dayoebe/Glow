<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog\Comment;
use App\Models\Blog\Post;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    public function index(string $slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $comments = Comment::with(['user', 'replies.user'])
            ->where('post_id', $post->id)
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
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        if (!$post->allow_comments) {
            return response()->json(['message' => 'Comments are disabled for this post.'], 403);
        }

        $data = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'author_email' => ['nullable', 'email', 'max:180'],
        ]);

        $parentId = $data['parent_id'] ?? null;
        if ($parentId) {
            $parentExists = Comment::where('id', $parentId)
                ->where('post_id', $post->id)
                ->exists();
            if (!$parentExists) {
                return response()->json(['message' => 'Invalid parent comment.'], 422);
            }
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'parent_id' => $parentId,
            'user_id' => $request->user()?->id,
            'author_name' => $data['author_name'] ?? null,
            'author_email' => $data['author_email'] ?? null,
            'comment' => $data['comment'],
            'is_approved' => true,
        ]);

        return response()->json([
            'data' => $this->formatComment($comment->fresh(['user', 'replies.user'])),
        ]);
    }

    public function like(string $slug, int $commentId)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $comment = Comment::where('post_id', $post->id)
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
