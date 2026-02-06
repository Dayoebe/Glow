<?php

namespace App\Livewire\Admin\News;

use App\Models\News\News;
use App\Models\News\NewsInteraction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Analytics extends Component
{
    public $range = '30';

    public function getRangeStartProperty()
    {
        if ($this->range === 'all') {
            return null;
        }

        $days = max(1, (int) $this->range);

        return now()->subDays($days)->startOfDay();
    }

    public function render()
    {
        $rangeStart = $this->rangeStart;
        $rangeLabel = $this->range === 'all' ? 'All time' : 'Last ' . $this->range . ' days';

        $newsQuery = News::query();
        if ($rangeStart) {
            $newsQuery->where('created_at', '>=', $rangeStart);
        }

        $totalPosts = (clone $newsQuery)->count();
        $publishedPosts = (clone $newsQuery)->where('is_published', true)->count();
        $pendingPosts = (clone $newsQuery)->where('approval_status', 'pending')->count();

        $todayCount = News::whereDate('created_at', now()->toDateString())->count();
        $weekCount = News::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthCount = News::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $avgPerDay = $totalPosts;
        if ($rangeStart) {
            $days = max(1, now()->startOfDay()->diffInDays($rangeStart) + 1);
            $avgPerDay = $totalPosts / $days;
        } else {
            $firstPostDate = News::min('created_at');
            if ($firstPostDate) {
                $days = max(1, now()->startOfDay()->diffInDays($firstPostDate) + 1);
                $avgPerDay = $totalPosts / $days;
            }
        }

        $interactionQuery = NewsInteraction::query();
        if ($rangeStart) {
            $interactionQuery->where('created_at', '>=', $rangeStart);
        }

        $totalViews = (clone $interactionQuery)->views()->count();
        $rawViews = News::sum('raw_views');
        $uniqueReaders = (clone $interactionQuery)
            ->views()
            ->whereNotNull('ip_address')
            ->distinct('ip_address')
            ->count('ip_address');
        $totalReactions = (clone $interactionQuery)->reactions()->count();
        $totalShares = (clone $interactionQuery)->shares()->count();

        $topPosters = DB::table('news')
            ->join('users', 'users.id', '=', 'news.author_id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('count(*) as total'))
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('news.created_at', '>=', $rangeStart);
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $worstPosters = DB::table('news')
            ->join('users', 'users.id', '=', 'news.author_id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('count(*) as total'))
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('news.created_at', '>=', $rangeStart);
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total')
            ->limit(5)
            ->get();

        $viewedPosts = DB::table('news')
            ->leftJoin('news_interactions', function ($join) use ($rangeStart) {
                $join->on('news.id', '=', 'news_interactions.news_id')
                    ->where('news_interactions.type', '=', 'view');
                if ($rangeStart) {
                    $join->where('news_interactions.created_at', '>=', $rangeStart);
                }
            })
            ->select(
                'news.id',
                'news.title',
                'news.slug',
                'news.featured_image',
                DB::raw('count(news_interactions.id) as views')
            )
            ->groupBy('news.id', 'news.title', 'news.slug', 'news.featured_image');

        $topViewed = (clone $viewedPosts)
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        $worstViewed = (clone $viewedPosts)
            ->orderBy('views')
            ->limit(5)
            ->get();

        $dailyCounts = DB::table('news')
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('count(*) as total'))
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('created_at', '>=', $rangeStart);
            })
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('day')
            ->limit(14)
            ->get()
            ->map(function ($row) {
                return [
                    'day' => $row->day,
                    'total' => (int) $row->total,
                ];
            });

        return view('livewire.admin.news.analytics', [
            'rangeLabel' => $rangeLabel,
            'stats' => [
                'total_posts' => $totalPosts,
                'published_posts' => $publishedPosts,
                'pending_posts' => $pendingPosts,
                'total_views' => $totalViews,
                'raw_views' => $rawViews,
                'unique_readers' => $uniqueReaders,
                'total_reactions' => $totalReactions,
                'total_shares' => $totalShares,
                'posts_today' => $todayCount,
                'posts_week' => $weekCount,
                'posts_month' => $monthCount,
                'avg_per_day' => $avgPerDay,
            ],
            'topPosters' => $topPosters,
            'worstPosters' => $worstPosters,
            'topViewed' => $topViewed,
            'worstViewed' => $worstViewed,
            'dailyCounts' => $dailyCounts,
        ])->layout('layouts.admin', ['header' => 'News Analytics']);
    }
}
