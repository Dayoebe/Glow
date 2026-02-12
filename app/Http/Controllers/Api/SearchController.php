<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post as BlogPost;
use App\Models\Event\Event;
use App\Models\News\News;
use App\Models\Podcast\Episode as PodcastEpisode;
use App\Models\Podcast\Show as PodcastShow;
use App\Models\Show\Show;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));
        $types = $this->parseTypes($request->query('types'));
        $limit = (int) $request->query('limit', 8);
        $limit = min(20, max(3, $limit));
        $sort = $request->query('sort', 'latest');
        $category = trim((string) $request->query('category', ''));
        $tag = trim((string) $request->query('tag', ''));

        if ($query === '' && $category === '' && $tag === '') {
            return response()->json([
                'query' => $query,
                'types' => $types,
                'filters' => [
                    'category' => $category ?: null,
                    'tag' => $tag ?: null,
                    'sort' => $sort,
                ],
                'results' => $this->emptyResults($types),
                'counts' => array_fill_keys($types, 0),
            ]);
        }

        $results = [];
        $counts = [];

        if (in_array('news', $types, true)) {
            $newsQuery = News::with('category')
                ->published();

            if ($query !== '') {
                $newsQuery->search($query);
            }
            if ($category !== '') {
                $newsQuery->byCategory($category);
            }
            if ($tag !== '') {
                $newsQuery->whereJsonContains('tags', $tag);
            }
            if ($sort === 'popular') {
                $newsQuery->orderBy('views', 'desc');
            } else {
                $newsQuery->latest('published_at');
            }

            $news = $newsQuery->take($limit)->get();
            $results['news'] = $news->map(fn ($item) => $this->mapNews($item))->values();
            $counts['news'] = $news->count();
        }

        if (in_array('blog', $types, true)) {
            $blogQuery = BlogPost::with('category')
                ->published();

            if ($query !== '') {
                $blogQuery->search($query);
            }
            if ($category !== '') {
                $blogQuery->byCategory($category);
            }
            if ($tag !== '') {
                $blogQuery->whereJsonContains('tags', $tag);
            }
            if ($sort === 'popular') {
                $blogQuery->orderBy('views', 'desc');
            } else {
                $blogQuery->latest('published_at');
            }

            $blog = $blogQuery->take($limit)->get();
            $results['blog'] = $blog->map(fn ($item) => $this->mapBlog($item))->values();
            $counts['blog'] = $blog->count();
        }

        if (in_array('events', $types, true)) {
            $eventQuery = Event::with('category')
                ->published();

            if ($query !== '') {
                $eventQuery->search($query);
            }
            if ($category !== '') {
                $eventQuery->byCategory($category);
            }
            if ($tag !== '') {
                $eventQuery->whereJsonContains('tags', $tag);
            }
            if ($sort === 'popular') {
                $eventQuery->orderBy('views', 'desc');
            } else {
                $eventQuery->orderBy('start_at', 'asc');
            }

            $events = $eventQuery->take($limit)->get();
            $results['events'] = $events->map(fn ($item) => $this->mapEvent($item))->values();
            $counts['events'] = $events->count();
        }

        if (in_array('shows', $types, true)) {
            $showQuery = Show::active();

            if ($query !== '') {
                $showQuery->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('full_description', 'like', "%{$query}%");
                });
            }

            if ($sort === 'popular') {
                $showQuery->orderBy('total_listeners', 'desc');
            } else {
                $showQuery->latest('created_at');
            }

            $shows = $showQuery->take($limit)->get();
            $results['shows'] = $shows->map(fn ($item) => $this->mapShow($item))->values();
            $counts['shows'] = $shows->count();
        }

        if (in_array('podcasts', $types, true)) {
            $podcastQuery = PodcastShow::active();

            if ($query !== '') {
                $podcastQuery->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                });
            }

            if ($sort === 'popular') {
                $podcastQuery->orderBy('total_plays', 'desc');
            } else {
                $podcastQuery->latest('created_at');
            }

            $podcasts = $podcastQuery->take($limit)->get();
            $results['podcasts'] = $podcasts->map(fn ($item) => $this->mapPodcastShow($item))->values();
            $counts['podcasts'] = $podcasts->count();
        }

        if (in_array('episodes', $types, true)) {
            $episodeQuery = PodcastEpisode::with('show')
                ->published();

            if ($query !== '') {
                $episodeQuery->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('show_notes', 'like', "%{$query}%");
                });
            }

            if ($sort === 'popular') {
                $episodeQuery->orderBy('plays', 'desc');
            } else {
                $episodeQuery->latest('published_at');
            }

            $episodes = $episodeQuery->take($limit)->get();
            $results['episodes'] = $episodes->map(fn ($item) => $this->mapPodcastEpisode($item))->values();
            $counts['episodes'] = $episodes->count();
        }

        return response()->json([
            'query' => $query,
            'types' => $types,
            'filters' => [
                'category' => $category ?: null,
                'tag' => $tag ?: null,
                'sort' => $sort,
            ],
            'results' => $results,
            'counts' => $counts,
        ]);
    }

    private function parseTypes(?string $types): array
    {
        $default = ['news', 'blog', 'podcasts', 'episodes', 'events', 'shows'];
        if (!$types) {
            return $default;
        }

        $parsed = collect(explode(',', $types))
            ->map(fn ($value) => strtolower(trim($value)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $parsed ?: $default;
    }

    private function emptyResults(array $types): array
    {
        $results = [];
        foreach ($types as $type) {
            $results[$type] = [];
        }
        return $results;
    }

    private function mapNews(News $news): array
    {
        return [
            'id' => $news->id,
            'type' => 'news',
            'title' => $news->title,
            'slug' => $news->slug,
            'excerpt' => $news->excerpt,
            'image' => $news->featured_image,
            'category' => $news->category?->name,
            'date' => $news->published_at?->format('Y-m-d'),
        ];
    }

    private function mapBlog(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'type' => 'blog',
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'image' => $post->featured_image,
            'category' => $post->category?->name,
            'date' => $post->published_at?->format('Y-m-d'),
        ];
    }

    private function mapEvent(Event $event): array
    {
        return [
            'id' => $event->id,
            'type' => 'event',
            'title' => $event->title,
            'slug' => $event->slug,
            'excerpt' => $event->excerpt,
            'image' => $event->featured_image,
            'category' => $event->category?->name,
            'date' => $event->start_at?->format('Y-m-d'),
            'location' => trim(implode(', ', array_filter([$event->venue_name, $event->city, $event->state]))),
        ];
    }

    private function mapShow(Show $show): array
    {
        return [
            'id' => $show->id,
            'type' => 'show',
            'title' => $show->title,
            'slug' => $show->slug,
            'excerpt' => $show->description,
            'image' => $show->cover_image,
        ];
    }

    private function mapPodcastShow(PodcastShow $show): array
    {
        return [
            'id' => $show->id,
            'type' => 'podcast',
            'title' => $show->title,
            'slug' => $show->slug,
            'excerpt' => $show->description,
            'image' => $show->cover_image,
            'category' => $show->category,
        ];
    }

    private function mapPodcastEpisode(PodcastEpisode $episode): array
    {
        return [
            'id' => $episode->id,
            'type' => 'podcast_episode',
            'title' => $episode->title,
            'slug' => $episode->slug,
            'show_slug' => $episode->show?->slug,
            'excerpt' => $episode->description,
            'image' => $episode->cover_image ?: $episode->show?->cover_image,
            'date' => $episode->published_at?->format('Y-m-d'),
        ];
    }
}
