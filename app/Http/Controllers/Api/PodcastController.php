<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Podcast\Episode;
use App\Models\Podcast\Show;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('search', ''));
        $perPage = (int) $request->query('per_page', 12);
        $perPage = min(24, max(6, $perPage));

        $query = Show::with(['host', 'publishedEpisodes'])
            ->withCount('publishedEpisodes')
            ->active();

        if ($category !== 'all') {
            $query->byCategory($category);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $shows = $query->orderBy('is_featured', 'desc')
            ->orderBy('total_plays', 'desc')
            ->paginate($perPage);

        $data = $shows->getCollection()
            ->map(fn ($show) => $this->mapShowCard($show))
            ->values();

        $latestEpisodes = Episode::with(['show'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get()
            ->map(fn ($episode) => $this->mapEpisodeCard($episode))
            ->values();

        $featuredShows = Show::with(['publishedEpisodes'])
            ->active()
            ->featured()
            ->take(3)
            ->get()
            ->map(fn ($show) => $this->mapShowCard($show))
            ->values();

        $trendingEpisodes = Episode::with(['show'])
            ->published()
            ->where('published_at', '>=', now()->subDays(30))
            ->orderBy('plays', 'desc')
            ->take(5)
            ->get()
            ->map(fn ($episode) => $this->mapEpisodeCard($episode))
            ->values();

        $categories = [
            'all' => 'All Podcasts',
            'music' => 'Music',
            'talk' => 'Talk Show',
            'interview' => 'Interviews',
            'tech' => 'Tech & Audio',
            'lifestyle' => 'Lifestyle',
            'education' => 'Educational',
        ];

        return response()->json([
            'data' => $data,
            'meta' => [
                'pagination' => [
                    'current_page' => $shows->currentPage(),
                    'last_page' => $shows->lastPage(),
                    'per_page' => $shows->perPage(),
                    'total' => $shows->total(),
                ],
                'latestEpisodes' => $latestEpisodes,
                'featuredShows' => $featuredShows,
                'trendingEpisodes' => $trendingEpisodes,
                'categories' => $categories,
            ],
        ]);
    }

    public function show(string $slug, Request $request)
    {
        $show = Show::with(['host', 'publishedEpisodes'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        $season = $request->query('season', 'all');
        $sortBy = $request->query('sort', 'latest');

        $episodeQuery = $show->publishedEpisodes();

        if ($season !== 'all') {
            $episodeQuery->bySeason($season);
        }

        switch ($sortBy) {
            case 'oldest':
                $episodeQuery->oldest('published_at');
                break;
            case 'popular':
                $episodeQuery->orderBy('plays', 'desc');
                break;
            default:
                $episodeQuery->latest('published_at');
        }

        $episodes = $episodeQuery->get()
            ->map(fn ($episode) => $this->mapEpisodeCard($episode))
            ->values();

        $seasons = $show->publishedEpisodes()
            ->whereNotNull('season_number')
            ->distinct()
            ->pluck('season_number')
            ->sort()
            ->values();

        return response()->json([
            'data' => $this->mapShowDetail($show),
            'episodes' => $episodes,
            'seasons' => $seasons,
        ]);
    }

    public function episode(string $showSlug, string $episodeSlug)
    {
        $episode = Episode::with(['show'])
            ->published()
            ->where('slug', $episodeSlug)
            ->whereHas('show', function ($query) use ($showSlug) {
                $query->where('slug', $showSlug);
            })
            ->firstOrFail();

        $related = Episode::published()
            ->where('show_id', $episode->show_id)
            ->where('id', '!=', $episode->id)
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(fn ($item) => $this->mapEpisodeCard($item));

        return response()->json([
            'data' => [
                'id' => $episode->id,
                'slug' => $episode->slug,
                'title' => $episode->title,
                'description' => $episode->description,
                'show_notes' => $episode->show_notes,
                'cover_image' => $episode->cover_image,
                'audio_file' => $episode->audio_file,
                'audio_format' => $episode->audio_format,
                'video_url' => $episode->video_url,
                'video_type' => $episode->video_type,
                'has_video' => $episode->has_video,
                'duration' => $episode->formatted_duration,
                'published_at' => $episode->published_date,
                'plays' => $episode->plays,
                'downloads' => $episode->downloads,
                'shares' => $episode->shares,
                'season_number' => $episode->season_number,
                'episode_number' => $episode->episode_number,
                'explicit' => $episode->explicit,
                'show' => [
                    'id' => $episode->show->id,
                    'slug' => $episode->show->slug,
                    'title' => $episode->show->title,
                ],
                'platform_links' => $episode->platform_links,
            ],
            'related' => $related,
        ]);
    }

    private function mapShowCard(Show $show): array
    {
        return [
            'id' => $show->id,
            'slug' => $show->slug,
            'title' => $show->title,
            'description' => $show->description,
            'cover_image' => $show->cover_image,
            'category' => $show->category,
            'host_name' => $show->host_name,
            'host' => $show->host ? [
                'id' => $show->host->id,
                'name' => $show->host->name,
            ] : null,
            'published_episodes_count' => $show->published_episodes_count,
            'total_plays' => $show->total_plays,
            'is_featured' => $show->is_featured,
        ];
    }

    private function mapShowDetail(Show $show): array
    {
        return [
            'id' => $show->id,
            'slug' => $show->slug,
            'title' => $show->title,
            'description' => $show->description,
            'cover_image' => $show->cover_image,
            'category' => $show->category,
            'host_name' => $show->host_name,
            'host' => $show->host ? [
                'id' => $show->host->id,
                'name' => $show->host->name,
            ] : null,
            'frequency' => $show->frequency,
            'language' => $show->language,
            'explicit' => $show->explicit,
            'tags' => $show->tags,
            'rss_feed_url' => $show->rss_feed_url,
            'spotify_url' => $show->spotify_url,
            'apple_url' => $show->apple_url,
            'google_url' => $show->google_url,
            'total_episodes' => $show->total_episodes,
            'total_plays' => $show->total_plays,
            'subscribers' => $show->subscribers,
            'average_rating' => $show->average_rating,
        ];
    }

    private function mapEpisodeCard(Episode $episode): array
    {
        return [
            'id' => $episode->id,
            'slug' => $episode->slug,
            'show_slug' => $episode->show?->slug,
            'title' => $episode->title,
            'description' => $episode->description,
            'image' => $episode->cover_image ?? $episode->show?->cover_image,
            'show_title' => $episode->show?->title,
            'duration' => $episode->formatted_duration,
            'published_at' => $episode->published_date,
            'plays' => $episode->plays,
            'video_url' => $episode->video_url,
            'has_video' => $episode->has_video,
            'season_episode' => $episode->season_number ? "S{$episode->season_number} E{$episode->episode_number}" : null,
        ];
    }
}
