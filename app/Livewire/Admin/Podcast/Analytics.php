<?php

namespace App\Livewire\Admin\Podcast;

use App\Models\Podcast\Download;
use App\Models\Podcast\Play;
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

        $basePlays = Play::query();
        $baseDownloads = Download::query();

        if ($rangeStart) {
            $basePlays->where('started_at', '>=', $rangeStart);
            $baseDownloads->where('downloaded_at', '>=', $rangeStart);
        }

        $totalPlays = (clone $basePlays)->count();
        $rawPlays = \App\Models\Podcast\Episode::sum('raw_plays');
        $uniqueListeners = (clone $basePlays)->distinct('session_id')->count('session_id');
        $avgCompletion = (clone $basePlays)->avg('completion_rate');
        $totalDownloads = (clone $baseDownloads)->count();
        $listenSeconds = (clone $basePlays)->sum('listen_duration');

        $stats = [
            'total_plays' => $totalPlays,
            'raw_plays' => $rawPlays,
            'unique_listeners' => $uniqueListeners,
            'avg_completion' => $avgCompletion ? round($avgCompletion, 1) : 0,
            'total_downloads' => $totalDownloads,
            'listen_hours' => $listenSeconds > 0 ? round($listenSeconds / 3600, 1) : 0,
        ];

        $topEpisodes = DB::table('podcast_plays')
            ->join('podcast_episodes', 'podcast_episodes.id', '=', 'podcast_plays.episode_id')
            ->select(
                'podcast_episodes.id',
                'podcast_episodes.title',
                'podcast_episodes.slug',
                'podcast_episodes.cover_image',
                DB::raw('count(*) as plays'),
                DB::raw('avg(podcast_plays.completion_rate) as avg_completion')
            )
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('podcast_plays.started_at', '>=', $rangeStart);
            })
            ->groupBy('podcast_episodes.id', 'podcast_episodes.title', 'podcast_episodes.slug', 'podcast_episodes.cover_image')
            ->orderByDesc('plays')
            ->limit(5)
            ->get();

        $topShows = DB::table('podcast_plays')
            ->join('podcast_episodes', 'podcast_episodes.id', '=', 'podcast_plays.episode_id')
            ->join('podcast_shows', 'podcast_shows.id', '=', 'podcast_episodes.show_id')
            ->select(
                'podcast_shows.id',
                'podcast_shows.title',
                'podcast_shows.cover_image',
                DB::raw('count(*) as plays'),
                DB::raw('count(distinct podcast_episodes.id) as episode_count')
            )
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('podcast_plays.started_at', '>=', $rangeStart);
            })
            ->groupBy('podcast_shows.id', 'podcast_shows.title', 'podcast_shows.cover_image')
            ->orderByDesc('plays')
            ->limit(5)
            ->get();

        $recentPlays = DB::table('podcast_plays')
            ->join('podcast_episodes', 'podcast_episodes.id', '=', 'podcast_plays.episode_id')
            ->join('podcast_shows', 'podcast_shows.id', '=', 'podcast_episodes.show_id')
            ->select(
                'podcast_plays.started_at',
                'podcast_plays.completion_rate',
                'podcast_plays.device_type',
                'podcast_plays.platform',
                'podcast_episodes.title as episode_title',
                'podcast_shows.title as show_title'
            )
            ->when($rangeStart, function ($query) use ($rangeStart) {
                $query->where('podcast_plays.started_at', '>=', $rangeStart);
            })
            ->orderByDesc('podcast_plays.started_at')
            ->limit(10)
            ->get();

        $deviceBreakdown = (clone $basePlays)
            ->select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        $platformBreakdown = (clone $basePlays)
            ->select('platform', DB::raw('count(*) as total'))
            ->groupBy('platform')
            ->orderByDesc('total')
            ->get();

        return view('livewire.admin.podcast.analytics', [
            'stats' => $stats,
            'rangeLabel' => $rangeLabel,
            'topEpisodes' => $topEpisodes,
            'topShows' => $topShows,
            'recentPlays' => $recentPlays,
            'deviceBreakdown' => $deviceBreakdown,
            'platformBreakdown' => $platformBreakdown,
        ])->layout('layouts.admin', ['header' => 'Podcast Analytics']);
    }
}
