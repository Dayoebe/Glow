<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast\Episode;
use App\Models\Podcast\Show;
use App\Models\Podcast\Subscription;
use Illuminate\Http\Request;

class PodcastEngagementController extends Controller
{
    public function subscriptionStatus(Request $request, string $slug)
    {
        $show = Show::where('slug', $slug)->active()->firstOrFail();
        $userId = $request->user()?->id;

        $subscribed = false;
        if ($userId) {
            $subscribed = Subscription::where('user_id', $userId)
                ->where('show_id', $show->id)
                ->exists();
        }

        return response()->json([
            'data' => [
                'subscribed' => $subscribed,
                'subscribers' => $show->subscribers,
            ],
        ]);
    }

    public function toggleSubscription(Request $request, string $slug)
    {
        $show = Show::where('slug', $slug)->active()->firstOrFail();
        $userId = $request->user()?->id;

        $subscription = Subscription::where('user_id', $userId)
            ->where('show_id', $show->id)
            ->first();

        if ($subscription) {
            $subscription->delete();
            $show->decrementSubscribers();
            $subscribed = false;
        } else {
            Subscription::create([
                'user_id' => $userId,
                'show_id' => $show->id,
                'notifications_enabled' => true,
                'subscribed_at' => now(),
            ]);
            $show->incrementSubscribers();
            $subscribed = true;
        }

        $show->refresh();

        return response()->json([
            'data' => [
                'subscribed' => $subscribed,
                'subscribers' => $show->subscribers,
            ],
        ]);
    }

    public function shareEpisode(Request $request, string $showSlug, string $episodeSlug)
    {
        $episode = Episode::published()
            ->where('slug', $episodeSlug)
            ->whereHas('show', function ($query) use ($showSlug) {
                $query->where('slug', $showSlug);
            })
            ->firstOrFail();

        $episode->increment('shares');

        return response()->json([
            'data' => [
                'shares' => $episode->shares,
            ],
        ]);
    }
}
