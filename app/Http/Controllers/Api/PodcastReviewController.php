<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast\Review;
use App\Models\Podcast\Show;
use Illuminate\Http\Request;

class PodcastReviewController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $show = Show::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $reviews = Review::with('user')
            ->where('show_id', $show->id)
            ->where('is_approved', true)
            ->latest()
            ->get()
            ->map(fn ($review) => $this->formatReview($review))
            ->values();

        $average = (float) (Review::where('show_id', $show->id)
            ->where('is_approved', true)
            ->avg('rating') ?? 0);

        $count = Review::where('show_id', $show->id)
            ->where('is_approved', true)
            ->count();

        $myReview = null;
        if ($request->user()) {
            $mine = Review::where('show_id', $show->id)
                ->where('user_id', $request->user()->id)
                ->first();
            if ($mine) {
                $myReview = $this->formatReview($mine);
            }
        }

        return response()->json([
            'data' => $reviews,
            'meta' => [
                'average' => round($average, 1),
                'count' => $count,
                'my_review' => $myReview,
            ],
        ]);
    }

    public function store(Request $request, string $slug)
    {
        $show = Show::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:2000'],
        ]);

        $review = Review::updateOrCreate(
            ['show_id' => $show->id, 'user_id' => $user->id],
            [
                'rating' => $data['rating'],
                'review' => $data['review'] ?? null,
                'is_approved' => true,
            ]
        );

        $average = (float) (Review::where('show_id', $show->id)
            ->where('is_approved', true)
            ->avg('rating') ?? 0);

        $count = Review::where('show_id', $show->id)
            ->where('is_approved', true)
            ->count();

        return response()->json([
            'data' => $this->formatReview($review->fresh(['user'])),
            'meta' => [
                'average' => round($average, 1),
                'count' => $count,
            ],
        ]);
    }

    public function helpful(Request $request, string $slug, int $reviewId)
    {
        $show = Show::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $review = Review::where('show_id', $show->id)
            ->where('id', $reviewId)
            ->firstOrFail();

        $review->increment('helpful_count');

        return response()->json([
            'data' => [
                'id' => $review->id,
                'helpful_count' => $review->helpful_count,
            ],
        ]);
    }

    private function formatReview(Review $review): array
    {
        $authorName = $review->user?->name ?? 'Listener';

        return [
            'id' => $review->id,
            'rating' => $review->rating,
            'review' => $review->review,
            'helpful_count' => $review->helpful_count ?? 0,
            'author' => [
                'name' => $authorName,
                'avatar' => $review->user?->avatar
                    ?? 'https://ui-avatars.com/api/?name=' . urlencode($authorName),
            ],
            'created_at' => $review->created_at?->toDateTimeString(),
            'created_human' => $review->created_at?->diffForHumans(),
        ];
    }
}
