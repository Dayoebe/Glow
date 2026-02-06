<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Blog\Comment;
use App\Models\Blog\Interaction;
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

        $baseInteractions = Interaction::query();
        $baseComments = Comment::query();

        if ($rangeStart) {
            $baseInteractions->where('created_at', '>=', $rangeStart);
            $baseComments->where('created_at', '>=', $rangeStart);
        }

        $totalViews = (clone $baseInteractions)->where('type', 'view')->count();
        $rawViews = \App\Models\Blog\Post::sum('raw_views');
        $uniqueReaders = (clone $baseInteractions)
            ->where('type', 'view')
            ->whereNotNull('ip_address')
            ->distinct('ip_address')
            ->count('ip_address');
        $totalReactions = (clone $baseInteractions)->where('type', 'reaction')->count();
        $totalComments = (clone $baseComments)->approved()->count();

        $postQuery = \App\Models\Blog\Post::query();
        if ($rangeStart) {
            $postQuery->where('created_at', '>=', $rangeStart);
        }

        $totalPosts = (clone $postQuery)->count();
        $publishedPosts = (clone $postQuery)->where('is_published', true)->count();
        $pendingPosts = (clone $postQuery)->where('approval_status', 'pending')->count();

        $todayCount = \App\Models\Blog\Post::whereDate('created_at', now()->toDateString())->count();
        $weekCount = \App\Models\Blog\Post::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthCount = \App\Models\Blog\Post::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $avgPerDay = $totalPosts;
        if ($rangeStart) {
            $days = max(1, now()->startOfDay()->diffInDays($rangeStart) + 1);
            $avgPerDay = $totalPosts / $days;
        } else {
            $firstPostDate = \App\Models\Blog\Post::min('created_at');
            if ($firstPostDate) {
                $days = max(1, now()->startOfDay()->diffInDays($firstPostDate) + 1);
                $avgPerDay = $totalPosts / $days;
            }
        }

        $stats = [
            'total_views' => $totalViews,
            'raw_views' => $rawViews,
            'unique_readers' => $uniqueReaders,
            'total_reactions' => $totalReactions,
            'total_comments' => $totalComments,
            'total_posts' => $totalPosts,
            'published_posts' => $publishedPosts,
            'pending_posts' => $pendingPosts,
            'posts_today' => $todayCount,
            'posts_week' => $weekCount,
            'posts_month' => $monthCount,
            'avg_per_day' => $avgPerDay,
        ];

        $topPosts = DB::table('blog_interactions')
            ->join('blog_posts', 'blog_posts.id', '=', 'blog_interactions.post_id')
            ->select(
                'blog_posts.id',
                'blog_posts.title',
                'blog_posts.slug',
                'blog_posts.featured_image',
                DB::raw('count(*) as views')
            )
            ->where('blog_interactions.type', 'view')
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('blog_interactions.created_at', '>=', $rangeStart);
            })
            ->groupBy('blog_posts.id', 'blog_posts.title', 'blog_posts.slug', 'blog_posts.featured_image')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        $worstViewedPosts = DB::table('blog_posts')
            ->leftJoin('blog_interactions', function ($join) use ($rangeStart) {
                $join->on('blog_posts.id', '=', 'blog_interactions.post_id')
                    ->where('blog_interactions.type', '=', 'view');
                if ($rangeStart) {
                    $join->where('blog_interactions.created_at', '>=', $rangeStart);
                }
            })
            ->select(
                'blog_posts.id',
                'blog_posts.title',
                'blog_posts.slug',
                'blog_posts.featured_image',
                DB::raw('count(blog_interactions.id) as views')
            )
            ->groupBy('blog_posts.id', 'blog_posts.title', 'blog_posts.slug', 'blog_posts.featured_image')
            ->orderBy('views')
            ->limit(5)
            ->get();

        $topPosters = DB::table('blog_posts')
            ->join('users', 'users.id', '=', 'blog_posts.author_id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('count(*) as total'))
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('blog_posts.created_at', '>=', $rangeStart);
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $worstPosters = DB::table('blog_posts')
            ->join('users', 'users.id', '=', 'blog_posts.author_id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('count(*) as total'))
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('blog_posts.created_at', '>=', $rangeStart);
            })
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total')
            ->limit(5)
            ->get();

        $dailyCounts = DB::table('blog_posts')
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

        $topCategories = DB::table('blog_interactions')
            ->join('blog_posts', 'blog_posts.id', '=', 'blog_interactions.post_id')
            ->join('blog_categories', 'blog_categories.id', '=', 'blog_posts.category_id')
            ->select(
                'blog_categories.id',
                'blog_categories.name',
                'blog_categories.color',
                DB::raw('count(*) as views')
            )
            ->where('blog_interactions.type', 'view')
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('blog_interactions.created_at', '>=', $rangeStart);
            })
            ->groupBy('blog_categories.id', 'blog_categories.name', 'blog_categories.color')
            ->orderByDesc('views')
            ->limit(5)
            ->get();

        $engagementMix = (clone $baseInteractions)
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        $reactionBreakdown = (clone $baseInteractions)
            ->where('type', 'reaction')
            ->select('value', DB::raw('count(*) as total'))
            ->groupBy('value')
            ->orderByDesc('total')
            ->get();

        $recentComments = DB::table('blog_comments')
            ->join('blog_posts', 'blog_posts.id', '=', 'blog_comments.post_id')
            ->select(
                'blog_comments.id',
                'blog_comments.comment',
                'blog_comments.is_approved',
                'blog_comments.created_at',
                'blog_posts.title as post_title'
            )
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('blog_comments.created_at', '>=', $rangeStart);
            })
            ->orderByDesc('blog_comments.created_at')
            ->limit(10)
            ->get();

        return view('livewire.admin.blog.analytics', [
            'stats' => $stats,
            'rangeLabel' => $rangeLabel,
            'topPosts' => $topPosts,
            'worstViewedPosts' => $worstViewedPosts,
            'topPosters' => $topPosters,
            'worstPosters' => $worstPosters,
            'topCategories' => $topCategories,
            'engagementMix' => $engagementMix,
            'reactionBreakdown' => $reactionBreakdown,
            'recentComments' => $recentComments,
            'dailyCounts' => $dailyCounts,
        ])->layout('layouts.admin', ['header' => 'Blog Analytics']);
    }
}
